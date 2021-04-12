<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Traits\CommonTrait;
use App\Traits\FileTrait;
use App\Traits\ValidationTrait;

use App\Banner;
use App\Logo;
use App\PrivacyPolicy;
use App\Guidelines;
use App\AboutUs;
use App\Packages;
use App\Tests;
use App\Admin;
use App\Help;

use League\Flysystem\Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CmsController extends Controller
{
    use FileTrait, CommonTrait, ValidationTrait;
    public $platform = 'backend';

    ////--( Banner )--////
    public function showCmsBanner()
    {
        $banner = Banner::all();
        return view('admin.cms.banner.banner', ['banner' => $banner]);
    }

    public function ajaxGetBanner()
    {
        try {

            $data = array();
            $i = 1;
            $banner = Banner::orderBy('id', 'desc')->get();

            foreach ($banner as $temp) {

                if ($temp->bannerFor == 'Test') {
                    $name = DB::table('tests')->where('id', $temp->testId)->value('testName');
                } else if ($temp->bannerFor == 'Package') {
                    $name = DB::table('packages')->where('id', $temp->packageId)->value('packageName');
                } else {
                    $name = 'NA';
                }

                if ($temp->adminId != null) {
                    $dcName = DB::table('admins')->where('id', $temp->adminId)->value('name');
                } else {
                    $dcName = 'NA';
                }

                $data[] = array(
                    "count" => $i,
                    'id' => $temp->id,
                    "image" => $this->picUrl($temp->image, 'bannerPic', $this->platform),
                    "dcName" => $dcName,
                    "bannerFor" => $temp->bannerFor,
                    "page" => $temp->page,
                    "name" => $name,
                    "status" => $temp->status,
                );
                $i++;
            }


            return Datatables::of($data)
                ->addColumn('action', function ($data) {

                    $itemPermission = $this->itemPermission();

                    if ($itemPermission['status_item'] == '1') {
                        if ($data["status"] == "0") {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.banner') . '" data-id="' . encrypt($data["id"]) . '" class="action" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        } else {
                            $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.banner') . '" data-id="' . encrypt($data["id"]) . '" class="action" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
                        }
                    } else {
                        $status = '';
                    }

                    if ($itemPermission['edit_item'] == '1') {
                        $edit = '<a target="_blank" href="' . route('edit.banner') . '/' . encrypt($data["id"]) . '" title="Update"><i class="md md-edit" style="font-size: 20px;"></i></a>';
                    } else {
                        $edit = '';
                    }

                    if ($itemPermission['delete_item'] == '1') {
                        $delete = '<a href="JavaScript:void(0);" data-type="delete"  data-status=""data-action="' .  route('delete.banner') . '" data-id="' . encrypt($data["id"]) . '" class="action" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
                    } else {
                        $delete = '';
                    }

                    // if ($itemPermission['details_item'] == '1') {
                    //     $detail = '<a target="_blank" href="' .  route('details.banner') . '/' . encrypt($data["id"]) . '" title="Details"><i class="md md-visibility" style="font-size: 20px; color: green;"></i></a>';
                    // } else {
                    //     $detail = '';
                    // }

                    return $status . ' ' . $edit . ' ' . $delete;
                    // return $status . ' ' . $edit . ' ' . $delete . ' ' . $detail;
                })
                ->make(true);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function ajaxGetBannerTestPackage(Request $request)
    {
        try {

            $data = array();

            if ($request->choseTestPackage == 'Test') {
                foreach (Tests::where('adminId', $request->adminId)->where('status', '1')->get() as $temp) {
                    $data[] = array(
                        'id' => $temp->id,
                        'name' => $temp->testName,
                    );
                }
            } else {
                foreach (Packages::where('adminId', $request->adminId)->where('status', '1')->get() as $temp) {
                    $data[] = array(
                        'id' => $temp->id,
                        'name' => $temp->packageName,
                    );
                }
            }

            if ($data) {
                return Response()->Json(['status' => 1, 'msg' => 'Status successfully changed.', 'data' => $data], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function addCmsBanner()
    {
        $dcAdmin = Admin::where('role_id', config('constants.dcAdmin'))->get();
        return view('admin.cms.banner.add_banner', ['dcAdmin' => $dcAdmin]);
    }

    public function saveCmsBanner(Request $request)
    {
        try {
            $values = $request->only('chosePage', 'adminId', 'choseTestPackage', 'testPackage');
            $file = $request->file('file');

            //--Checking The Validation--//
            if ($values['chosePage'] == config('constants.dcHome')) {
                $validator = $this->isValid($request->all(), 'saveBannerDcHome', 0, $this->platform);
                if ($validator->fails()) {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
                }
            } else {
                $validator = $this->isValid($request->all(), 'saveBannerOffer', 0, $this->platform);
                if ($validator->fails()) {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
                }
            }

            //--Insert Banner--//
            if (!empty($file)) {
                $image = $this->uploadPicture($file, '', $this->platform, 'bannerPic');
                if ($image === false) {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                }
            } else {
                $image = "NA";
            }

            $banner = new Banner;

            if ($values['chosePage'] == config('constants.dcHome')) {
                $banner->page = config('constants.dcHome');
            } else {
                $banner->page = config('constants.offer');

                $banner->adminId = $values['adminId'];

                if ($values['choseTestPackage'] == 'Test') {
                    $banner->testId = $values['testPackage'];
                    $banner->name = DB::table('tests')->where('id', $values['testPackage'])->value('testName');
                } else {
                    $banner->packageId = $values['testPackage'];
                    $banner->name = DB::table('packages')->where('id', $values['testPackage'])->value('packageName');
                }

                $banner->bannerFor = $values['choseTestPackage'];
            }

            $banner->image = $image;

            if ($banner->save()) {
                return Response()->Json(['status' => 1, 'msg' => 'Banner Successfully saved.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function editCmsBanner($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $banner = Banner::find($id);

            $data = array();
            $dcAdmin = Admin::where('role_id', config('constants.dcAdmin'))->get();


            $testPackage = array();

            if ($banner->bannerFor == 'Test') {
                foreach (Tests::where('adminId', $banner->adminId)->get() as $temp) {
                    $testPackage[] = array(
                        'id' => $temp->id,
                        'name' => $temp->testName,
                    );
                }
            } else {
                foreach (Packages::where('adminId', $banner->adminId)->get() as $temp) {
                    $testPackage[] = array(
                        'id' => $temp->id,
                        'name' => $temp->packageName,
                    );
                }
            }

            $data = array(
                'id' => $banner->id,
                'image' => $this->picUrl($banner->image, 'bannerPic', $this->platform),
                'adminId' => $banner->adminId,
                'bannerFor' => $banner->bannerFor,
                'name' => ($banner->bannerFor == 'Test') ? DB::table('tests')->where('id', $banner->testId)->value('testName') : DB::table('packages')->where('id', $banner->packageId)->value('packageName'),
                'testPackageId' => ($banner->bannerFor == 'Test') ? $banner->testId : $banner->packageId,
                'testPackage' => $testPackage,

                'page' => $banner->page,

                'dcAdmin' => $dcAdmin
            );

            return view('admin.cms.banner.edit_banner', ["data" => $data]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function updateCmsBanner(Request $request)
    {
        $values = $request->only('id', 'chosePage', 'adminId', 'choseTestPackage', 'testPackage');
        $file = $request->file('file');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            //--Checking The Validation--//
            if ($values['chosePage'] == config('constants.dcHome')) {
                $validator = $this->isValid($request->all(), 'updateBannerDcHome', 0, $this->platform);
                if ($validator->fails()) {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
                }
            } else {
                $validator = $this->isValid($request->all(), 'updateBannerOffer', 0, $this->platform);
                if ($validator->fails()) {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.vErrMsg'), 'errors' => $validator->errors()], config('constants.ok'));
                }
            }

            $banner = Banner::find($id);

            if (!empty($file)) {
                $image = $this->uploadPicture($file, $banner->image, $this->platform, 'bannerPic');
                if ($image === false) {
                    return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
                } else {
                    $banner->image = $image;
                }
            }


            if ($values['chosePage'] == config('constants.dcHome')) {
                $banner->page = config('constants.dcHome');
                $banner->adminId = null;
                $banner->testId = null;
                $banner->packageId = null;
                $banner->name = 'NA';
                $banner->bannerFor = 'NA';
            } else {
                $banner->page = config('constants.offer');

                $banner->adminId = $values['adminId'];

                if ($values['choseTestPackage'] == 'Test') {
                    $banner->testId = $values['testPackage'];
                    $banner->packageId = null;
                    $banner->name = DB::table('tests')->where('id', $values['testPackage'])->value('testName');
                } else {
                    $banner->testId = null;
                    $banner->packageId = $values['testPackage'];
                    $banner->name = DB::table('packages')->where('id', $values['testPackage'])->value('packageName');
                }

                $banner->bannerFor = $values['choseTestPackage'];
            }


            if ($banner->update()) {
                return Response()->Json(['status' => 1, 'msg' => 'Banner Successfully updated.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function cmsBannerStatus($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->changeStatus($id, 'Banner');
            if ($result === true) {
                return response()->Json(['status' => 1, 'msg' => 'Status successfully changed.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function cmsBannerDelete($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }

        try {
            $result = $this->deleteItem($id, 'Banner', config('constants.bannerPic'));
            if ($result === true) {
                return response()->Json(['status' => 1, 'msg' => 'Successfully data Deleted.'], config('constants.ok'));
            } else {
                return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
            }
        } catch (Exception $e) {
            return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
        }
    }

    public function detailsCmsBanner($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $banner = Banner::where('id', $id)->first();
            return view('admin.cms.banner.detail_banner')->with('banner', $banner);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }


    ////--( Logo )--////
    public function cmsLogoShow()
    {
        $logo = Logo::where('count', 1)->orderBy('id', 'asc')->get();
        return view('admin.cms.logo.logo', ['logo' => $logo]);
    }

    public function cmsLogoAddSave(Request $request)
    {
        try {

            if ($request->check == 1) {
                $file = $request->file('file');
                $validator = $this->isValid($request->all(), 'logoAdd', 0, $this->platform);
                if ($validator->fails()) {
                    return response()->json(['status' => 0, 'msg' => 'Validation Error Occurs.', 'errors' => $validator->errors()], 200);
                } else {
                    $imgType = 'bigLogoPic';
                    $image = $this->uploadPicture($file, '', $this->platform, $imgType);
                    if ($image === false) {
                        return response()->json(['status' => 0, 'msg' => 'Image Could Not Upload.', 'errors' => $validator->errors()], 200);
                    } else {
                        if (Logo::where('count', 1)->count() >= 10) {
                            return response()->json(['status' => 0, 'ff' => Logo::count(), 'msg' => 'You cant add more than 10 item.', 'errors' => $validator->errors()], 200);
                        } else {
                            $logo = new Logo;
                            $logo->image = $image;
                            $logo->count = 1;
                            if ($logo->save()) {
                                return response()->json(['status' => 1, 'msg' => 'Logo successfully add', 'errors' => []], 200);
                            } else {
                                return response()->json(['status' => 0, 'msg' => 'Something went wrong.', 'errors' => []], 200);
                            }
                        }
                    }
                }
            } else if ($request->check == 2) {
                $file = $request->file('file');
                $validator = $this->isValid($request->all(), 'logoAdd', 0, $this->platform);
                if ($validator->fails()) {
                    return response()->json(['status' => 0, 'msg' => 'Validation Error Occurs.', 'errors' => $validator->errors()], 200);
                } else {
                    $imgType = 'smallLogoPic';
                    $image = $this->uploadPicture($file, '', $this->platform, $imgType);
                    if ($image === false) {
                        return response()->json(['status' => 0, 'msg' => 'Image Could Not Upload.', 'errors' => $validator->errors()], 200);
                    } else {
                        $logo = new Logo;
                        $logo->image = $image;
                        $logo->count = 2;
                        $logo->small_logo = $request->id;
                        if ($logo->save()) {
                            return response()->json(['status' => 1, 'msg' => 'Logo successfully add', 'errors' => []], 200);
                        } else {
                            return response()->json(['status' => 0, 'msg' => 'Something went wrong.', 'errors' => []], 200);
                        }
                    }
                }
            } else {
                $file = $request->file('file');
                $validator = $this->isValid($request->all(), 'logoAdd', 0, $this->platform);
                if ($validator->fails()) {
                    return response()->json(['status' => 0, 'msg' => 'Validation Error Occurs.', 'errors' => $validator->errors()], 200);
                } else {
                    $imgType = 'favIconPic';
                    $image = $this->uploadPicture($file, '', $this->platform, $imgType);
                    if ($image === false) {
                        return response()->json(['status' => 0, 'msg' => 'Image Could Not Upload.', 'errors' => $validator->errors()], 200);
                    } else {
                        $logo = new Logo;
                        $logo->image = $image;
                        $logo->count = 3;
                        $logo->fav_icon = $request->id;
                        if ($logo->save()) {
                            return response()->json(['status' => 1, 'msg' => 'Logo successfully add', 'errors' => []], 200);
                        } else {
                            return response()->json(['status' => 0, 'msg' => 'Something went wrong.', 'errors' => []], 200);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 0, 'msg' => 'Something went wrong.', 'errors' => []], 200);
        }
    }

    public function cmsLogoStatus($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $status = Logo::where('id', $id)->value('status');
            if ($status == 1) {
                return redirect()->back()->with('error', 'You cant change the state, atleat one must be active');
            } else {

                foreach (Logo::get() as $key) {
                    $logo = Logo::where('id', $key->id)->first();
                    $logo->status = '0';
                    $logo->update();
                }

                $result = $this->changeStatus($id, 'Logo');

                $logo = Logo::where('small_logo', $id)->value('id');
                if ($logo == null) {
                    goto a;
                } else {
                    $result = $this->changeStatus($logo, 'Logo');
                }

                a:
                $logo = Logo::where('fav_icon', $id)->value('id');
                if ($logo == null) {
                    goto b;
                } else {
                    $result = $this->changeStatus($logo, 'Logo');
                }

                b:
                if ($result === true) {
                    return redirect()->back()->with('success', 'Status successfully changed.');
                } else {
                    return redirect()->back()->with('error', 'Something went wrong.');
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function cmsLogoDelete($id)
    {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {

            $status = Logo::where('id', $id)->value('status');
            if ($status == 1) {
                return redirect()->back()->with('error', 'You cant delete the active one.');
            } else {
                $result = $this->deleteItem($id, 'Logo', config('constants.bigLogoPic'));

                if ($result) {
                    $logo = Logo::where('small_logo', $id)->value('id');
                    if ($logo == null) {
                        goto a;
                    } else {
                        $result = $this->deleteItem($logo, 'Logo', config('constants.smallLogoPic'));
                        goto a;
                    }
                }

                a:
                if ($result) {
                    $logo = Logo::where('fav_icon', $id)->value('id');
                    if ($logo == null) {
                        goto b;
                    } else {
                        $result = $this->deleteItem($logo, 'Logo', config('constants.favIconPic'));
                        goto b;
                    }
                }

                b:
                if ($result === true) {
                    return redirect()->back()->with('success', 'Successfully deleted.');
                } else {
                    return redirect()->back()->with('error', '2 Something went wrong.');
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', '1 Something went wrong.');
        }
    }






    ////-- ( Privacy Policy ) --////
    public function showPrivacyPolicy()
    {
        try {
            $privacyPolicy = DB::table('privacy_policy')->first();
            return view('admin.cms.privacy_policy.privacy_policy', ['privacyPolicy' => $privacyPolicy]);
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function savePrivacyPolicy(Request $request)
    {
        $values = $request->only('id', 'privacyPolicy');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {

            $privacyPolicy = PrivacyPolicy::find($id);

            $privacyPolicy->privacyPolicy = $values['privacyPolicy'];

            if ($privacyPolicy->save()) {
                return redirect()->back()->with('success', 'Privacy Policy successfully save.');
            } else {
                return redirect()->back()->with('error', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    ////-- ( Terms Conditions ) --////
    public function showGuidelines()
    {
        try {
            $guidelines = Guidelines::first();
            return view('admin.cms.guidelines.guidelines', ['guidelines' => $guidelines]);
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function saveGuidelines(Request $request)
    {
        $values = $request->only('id', 'guidelines');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {

            $guidelines = Guidelines::find($id);

            $guidelines->text = $values['guidelines'];

            if ($guidelines->save()) {
                return redirect()->back()->with('success', 'Terms Condition successfully save.');
            } else {
                return redirect()->back()->with('error', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    ////-- ( About Us ) --////
    public function showAboutUs()
    {
        try {
            $aboutUs = AboutUs::first();
            return view('admin.cms.about_us.about_us', ['aboutUs' => $aboutUs]);
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function saveAboutUs(Request $request)
    {
        $values = $request->only('id', 'aboutUs');

        try {
            $id = decrypt($values['id']);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }

        try {
            $aboutUs = AboutUs::find($id);
            $aboutUs->aboutUs = $values['aboutUs'];

            if ($aboutUs->save()) {
                return redirect()->back()->with('success', 'About Us successfully save.');
            } else {
                return redirect()->back()->with('error', 'Something went wrong.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }




    // public function showFaq()
    // {
    //     return view('admin.cms.faq.faq_list');
    // }

    // public function ajaxGetFaq()
    // {
    //     try {
    //         $faq = Faq::select('id', 'question', 'answer', 'status');

    //         return Datatables::of($faq)
    //             ->addIndexColumn()
    //             ->addColumn('question', function ($data) {
    //                 $question = substr($data->question, 0, 50) . '...';
    //                 return $question;
    //             })
    //             ->addColumn('answer', function ($data) {
    //                 $answer = substr($data->answer, 0, 50) . '...';
    //                 return $answer;
    //             })
    //             ->addColumn('status', function ($data) {

    //                 if ($data->status == '0') {
    //                     $status = '<span class="label label-danger">Blocked</span>';
    //                 } else {
    //                     $status = '<span class="label label-success">Active</span>';
    //                 }
    //                 return $status;
    //             })
    //             ->addColumn('action', function ($data) {

    //                 $itemPermission = $this->itemPermission();

    //                 $dataArray = [
    //                     'id' => encrypt($data->id),
    //                 ];

    //                 if ($itemPermission['status_item'] == '1') {
    //                     if ($data->status == "0") {
    //                         $status = '<a href="JavaScript:void(0);" data-type="status" data-status="unblock" data-action="' . route('status.faq') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Block"><i class="md md-lock" style="font-size: 20px; color: #2bbbad;"></i></a>';
    //                     } else {
    //                         $status = '<a href="JavaScript:void(0);" data-type="status" data-status="block" data-action="' . route('status.faq') . '/' . $dataArray['id'] . '" class="actionDatatable" title="Unblock"><i class="md md-lock-open" style="font-size: 20px; color: #2bbbad;"></i></a>';
    //                     }
    //                 } else {
    //                     $status = '';
    //                 }

    //                 if ($itemPermission['edit_item'] == '1') {
    //                     $edit = '<a target="_blank" href="' . route('edit.faq') . '/' . $dataArray['id'] . '" title="Edit"><i class="md md-edit" style="font-size: 20px;"></i></a>';
    //                 } else {
    //                     $edit = '';
    //                 }

    //                 if ($itemPermission['delete_item'] == '1') {
    //                     $delete = '<a href="JavaScript:void(0);" data-action="' . route('delete.faq') . '/' . $dataArray['id'] . '" data-type="delete" class="actionDatatable" title="Delete"><i class="md md-delete" style="font-size: 20px; color: red;"></i></a>';
    //                 } else {
    //                     $delete = '';
    //                 }

    //                 if ($itemPermission['details_item'] == '1') {
    //                     $detail = '<a target="_blank" href="' .  route('details.faq') . '/' . $dataArray['id'] . '" title="Details"><i class="md md-visibility" style="font-size: 20px; color: green;"></i></a>';
    //                 } else {
    //                     $detail = '';
    //                 }

    //                 return $status . ' ' . $edit . ' ' . $delete . ' ' . $detail;
    //             })
    //             ->rawColumns(['question', 'answer', 'status', 'action'])
    //             ->make(true);
    //     } catch (Exception $e) {
    //         return redirect()->back()->with('error', 'Something went wrong.');
    //     }
    // }

    // public function deleteFaq($id)
    // {
    //     try {
    //         $id = decrypt($id);
    //     } catch (DecryptException $e) {
    //         return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
    //     }

    //     try {
    //         $result = $this->deleteItem($id, 'Faq');
    //         if ($result === true) {
    //             return response()->Json(['status' => 1, 'msg' => 'Successfully data Deleted.'], config('constants.ok'));
    //         } else {
    //             return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
    //         }
    //     } catch (Exception $e) {
    //         return Response()->Json(['status' => 0, 'msg' => config('constants.serverErrMsg')], config('constants.ok'));
    //     }
    // }

    // public function detailsFaq($id)
    // {
    //     try {
    //         $id = decrypt($id);
    //     } catch (DecryptException $e) {
    //         return redirect()->back()->with('error', 'Something went wrong.');
    //     }

    //     try {
    //         $faq = Faq::where('id', $id)->first();
    //         return view('admin.cms.faq.faq_detail')->with('data', $faq);
    //     } catch (Exception $e) {
    //         return redirect()->back()->with('error', 'Something went wrong.');
    //     }
    // }
}
