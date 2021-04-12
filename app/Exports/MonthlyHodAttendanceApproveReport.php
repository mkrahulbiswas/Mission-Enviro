<?php

namespace App\Exports;

use App\Attendance;
use App\AttedanceRequest;

use App\User;
use App\Department;
use App\Branch;

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


class MonthlyHodAttendanceApproveReport implements FromView
{

	protected $branch;
	protected $department;
	protected $user;
	protected $fromDate;
	protected $toDate;

	public function __construct($branch, $department, $user, $fromDate, $toDate)
	{
		$this->branch = $branch;
		$this->department = $department;
		$this->user = $user;
		$this->fromDate = $fromDate;
		$this->toDate = $toDate;
	}

	public function view(): View
	{

		$i = 1;
		$dataOne = array();
		$dataTwo = array();
		$dataThree = array();
		$dataFour = array();
		$whereId = array();
		$query = "`status` IN (1,0)";

		// if (!empty($this->branch)) {
		// 	$query .= " and `branchId` = '$this->branch'";
		// }

		// if (!empty($this->department)) {
		// 	$query .= " and `departmentId` = '$this->department'";
		// }

		// if (!empty($this->user)) {
		// 	$query .= " and `id` = '$this->user'";
		// }

		/*---( OLD )---*/
		// $user = User::orderBy('id', 'desc')->whereRaw($query)->select('id', 'name', 'empCode', 'reportingId')->groupBy('reportingId')->get();
		// $day = (new DateTime(date('Y-m-d', strtotime($this->fromDate))))->diff(new DateTime(date('Y-m-d', strtotime($this->toDate))))->days;

		// foreach ($user as $key => $val) {
		// 	if ($val->reportingId != null) {
		// 		array_push($whereId, $val->reportingId);
		// 	}
		// }
		// $user = User::orderBy('id', 'desc')->select('id', 'name', 'empCode', 'departmentId', 'branchId')->whereIn('id', $whereId)->get();

		// foreach ($user as $tempOne) {

		// 	$totalAttendance = 0;

		// 	for ($x = 0; $x <= $day; $x++) {
		// 		$getOnebyOneDay = date('Y-m-d', strtotime('+' . $x . ' day', strtotime($this->fromDate)));
		// 		$attedanceRequest = AttedanceRequest::where([['punchDay', $getOnebyOneDay], ['status', 'Approved']])->groupBy('userId')->get();
		// 		foreach ($attedanceRequest as $tempTwo) {
		// 			$check = explode('-', $tempTwo->approvedBy);
		// 			if ($check[0] == $tempOne->id) {
		// 				$totalAttendance++;
		// 			}
		// 		}
		// 	}

		// 	$dataOne[] = array(
		// 		'selial' => $i,
		// 		'empCode' => $tempOne->empCode,
		// 		'name' => $tempOne->name,
		// 		'department' => Department::where('id', $tempOne->departmentId)->value('name'),
		// 		'branch' => Branch::where('id', $tempOne->branchId)->value('branchName'),
		// 		'totalAttendance' => $totalAttendance,
		// 	);
		// 	$i++;
		// }
		/*---( OLD )---*/

		$hodList = User::groupBy('reportingId')->where('status', '1')->select('reportingId', 'branchId', 'name')->get();
		foreach ($hodList as $tempOne) {
			if ($tempOne->reportingId != null) {
				$userList = User::where('reportingId', $tempOne->reportingId)->where('status', '1')->select('name', 'id', 'empCode')->get();
				foreach ($userList as $tempTwo) {

					$totalApprove = AttedanceRequest::where([['userId', $tempTwo->id], ['status', config('constants.approved')]])->whereBetween('punchingAt', [date('Y-m-d', strtotime($this->fromDate)), date('Y-m-d', strtotime($this->toDate))])->get()->count();
					$totalLate = AttedanceRequest::where([['userId', $tempTwo->id], ['status', config('constants.lateApprove')]])->whereBetween('punchingAt', [date('Y-m-d', strtotime($this->fromDate)), date('Y-m-d', strtotime($this->toDate))])->get()->count();
					$totalRejected = AttedanceRequest::where([['userId', $tempTwo->id], ['status', config('constants.rejected')]])->whereBetween('punchingAt', [date('Y-m-d', strtotime($this->fromDate)), date('Y-m-d', strtotime($this->toDate))])->get()->count();
					$totalPending = AttedanceRequest::where([['userId', $tempTwo->id], ['status', config('constants.pending')]])->whereBetween('punchingAt', [date('Y-m-d', strtotime($this->fromDate)), date('Y-m-d', strtotime($this->toDate))])->get()->count();

					$dataTwo[] = array(
						'empId' => $tempTwo->id,
						'empName' => $tempTwo->name,
						'empCode' => $tempTwo->empCode,

						'totalApprove' => $totalApprove,
						'totalLate' => $totalLate,
						'totalRejected' => $totalRejected,
						'totalPending' => $totalPending,

						'totalAll' => $totalApprove + $totalLate + $totalRejected + $totalPending,
					);
				}
				$dataOne[] = array(
					'hodId' => $tempOne->reportingId,
					'branchCode' => Branch::where('id', $tempOne->branchId)->value('branchCode'),
					'hodName' => User::where('id', $tempOne->reportingId)->value('name'),
					'dataTwo' => $dataTwo
				);
				$dataTwo = array();
			}
		}
		// dd($dataOne);
		return view('admin.reports.monthly_hod_attendance_approve_report.excel', ['dataOne' => $dataOne, 'fromDate' => $this->fromDate, 'toDate' => $this->toDate]);
	}
}
