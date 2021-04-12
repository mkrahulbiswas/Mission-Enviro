<?php

namespace App\Exports;

use App\Attendance;
use App\User;
use App\Department;
use App\Branch;

use Illuminate\Support\Facades\DB;

use DateTime;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class TopRankedExport implements FromView
{

	protected $taskLevel;
	protected $taskQuarter;
	protected $level;

	public function __construct($taskLevel, $taskQuarter, $level)
	{
		$this->branch = $taskLevel;
		$this->department = $taskQuarter;
		$this->user = $level;
	}


	public function view(): View
	{
		$i = 1;
		$dataOne = array();
		$dataTwo = array();
		$dataThree = array();
		$query = "`status` IN (1,0)";

		if (!empty($this->branch)) {
			$query .= " and `branchId` = '$this->branch'";
		}

		if (!empty($this->department)) {
			$query .= " and `departmentId` = '$this->department'";
		}

		if (!empty($this->user)) {
			$query .= " and `id` = '$this->user'";
		}

		$user = User::orderBy('id', 'desc')->where('status', '1')->whereRaw($query)->select('id', 'name', 'empCode', 'departmentId', 'timesheetId')->get();
		$day = (new DateTime(date('Y-m-d', strtotime($this->fromDate))))->diff(new DateTime(date('Y-m-d', strtotime($this->toDate))))->days;

		for ($x = 0; $x <= $day; $x++) {
			$getOnebyOneDay = date('Y-m-d', strtotime('+' . $x . ' day', strtotime($this->fromDate)));
			$dataThree[] = array(
				$x => $getOnebyOneDay,
			);
		}

		foreach ($user as $tempOne) {

			$totalPresent = 0;
			$totalAbsent = 0;
			$totalOffDay = 0;

			for ($x = 0; $x <= $day; $x++) {
				$getOnebyOneDay = date('Y-m-d', strtotime('+' . $x . ' day', strtotime($this->fromDate)));
				$getday = date("D", strtotime($getOnebyOneDay));
				$attendanceCount = Attendance::where('userId', $tempOne->id)->where('punchDay', $getOnebyOneDay)->count();

				if ($attendanceCount > 0) {

					$inTime = Attendance::where('userId', $tempOne->id)->where('punchDay', $getOnebyOneDay)->orderBy('id', 'asc')->first();
					$outTime = Attendance::where('userId', $tempOne->id)->where('punchDay', $getOnebyOneDay)->orderBy('id', 'desc')->first();

					$shiftStartTime = date('Y-m-d', strtotime($inTime['punchingAt'])) . ' ' . DB::table('timesheets')->where('id', $tempOne->timesheetId)->first()->timeFrom;
					$shiftEndTime = date('Y-m-d', strtotime($inTime['punchingAt'])) . ' ' . DB::table('timesheets')->where('id', $tempOne->timesheetId)->first()->timeTo;
					if ($attendanceCount == 1) {
						if (strtotime($inTime['punchingAt']) < strtotime($shiftStartTime)) {
							$outTime = '';
						} else if (strtotime($outTime['punchingAt']) > strtotime($shiftEndTime)) {
							$inTime = '';
						} else {

							$diff1 = strtotime($inTime['punchingAt']) - strtotime($shiftStartTime);
							$diff2 = strtotime($shiftEndTime) - strtotime($inTime['punchingAt']);
							// echo $diff1;

							if ($diff1 < $diff2) {
								$outTime = '';
							} else if ($diff1 == $diff2) {
								$inTime = '';
							} else {
								$inTime = '';
							}
						}
					}

					$totalPresent += ($getday == 'Sun') ? 1 : 1;
					$dataTwo[] = array(
						'getOnebyOneDay' => $getOnebyOneDay,
						'getDay' => $getday,

						'inTime' => ($inTime == '') ? '--' : date('H:i', strtotime($inTime['punchingAt'])),
						'outTime' => ($outTime == '') ? '--' : date('H:i', strtotime($outTime['punchingAt'])),
						'totalTime' => ($inTime == '' || $outTime == '') ? '--'
							: (new DateTime($inTime['punchingAt']))->diff(new DateTime($outTime['punchingAt']))->format('%H:%I'),

						'status' => ($getday == 'Sun') ? 'WOP' : 'P',

					);
				} else {
					$totalAbsent += ($getday == 'Sun') ? 0 : 1;
					$totalOffDay += ($getday == 'Sun') ? 1 : 0;

					$dataTwo[] = array(
						'getOnebyOneDay' => date('d-M-y', strtotime($getOnebyOneDay)),
						'getDay' => $getday,

						'inTime' => false,
						'outTime' => false,
						'totalTime' => "00:00",

						'status' => ($getday == 'Sun') ? 'WO' : 'A',
					);
				}
			}

			$dataOne[] = array(
				'selial' => $i,
				'empCode' => $tempOne->empCode,
				'name' => $tempOne->name,
				'department' => Department::where('id', $tempOne->departmentId)->value('name'),
				'branch' => Branch::where('id', $this->branch)->value('branchName'),
				'totalPresent' => $totalPresent,
				'totalAbsent' => $totalAbsent,
				'totalOffDay' => $totalOffDay,
				'dataTwo' => $dataTwo,
				'dataThree' => $dataThree,
			);

			$dataTwo = array();
			$i++;
		}
		// exit();
		// dd($dataOne);
		return view('admin.reports.monthly_work_duration_report.excel', ['dataOne' => $dataOne]);
	}
}
