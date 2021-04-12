<?php

namespace app\Traits;

use Exception;
use Image;

trait FileTrait
{
    public static function picUrl($pic, $imgType, $platform)
    {
        //echo $pic;exit();
        $avatarUrl = config('constants.baseUrl') . config('constants.avatar');
        $avatar = 'no_image.png';

        if ($imgType == 'adminPic') {
            $url = config('constants.baseUrl') . config('constants.adminPic');
            $avatar = 'admin_avatar.png';
        } else if ($imgType == 'userPic') {
            $url = config('constants.baseUrl') . config('constants.userPic');
            $avatar = 'admin_avatar.png';
        } else if ($imgType == 'bannerPic') {
            $url = config('constants.baseUrl') . config('constants.bannerPic');
            $avatar = 'no_image.png';
        } else if ($imgType == 'bigLogoPic') {
            $url = config('constants.baseUrl') . config('constants.bigLogoPic');
            $avatar = 'no_image.png';
        } else if ($imgType == 'smallLogoPic') {
            $url = config('constants.baseUrl') . config('constants.smallLogoPic');
            $avatar = 'no_image.png';
        } else if ($imgType == 'favIconPic') {
            $url = config('constants.baseUrl') . config('constants.favIconPic');
            $avatar = 'no_image.png';
        } else if ($imgType == 'didYouKnowPic') {
            $url = config('constants.baseUrl') . config('constants.didYouKnowPic');
            $avatar = 'no_image.png';
        } else if ($imgType == 'enviroVocabularyPic') {
            $url = config('constants.baseUrl') . config('constants.enviroVocabularyPic');
            $avatar = 'no_image.png';
        } else if ($imgType == 'freeDownloadPic') {
            $url = config('constants.baseUrl') . config('constants.freeDownloadPic');
            $avatar = 'no_image.png';
        } else if ($imgType == 'freeDownloadsFile') {
            $url = config('constants.baseUrl') . config('constants.freeDownloadsFile');
            $avatar = 'no_image.png';
        } else if ($imgType == 'pledgeFile') {
            $url = config('constants.baseUrl') . config('constants.pledgeFile');
            $avatar = 'no_image.png';
        } else if ($imgType == 'journalPic') {
            $url = config('constants.baseUrl') . config('constants.journalPic');
            $avatar = 'no_image.png';
        } else if ($imgType == 'taskCompletePic') {
            $url = config('constants.baseUrl') . config('constants.taskCompletePic');
            $avatar = 'no_image.png';
        } else if ($imgType == 'taskQuestionPic') {
            $url = config('constants.baseUrl') . config('constants.taskQuestionPic');
            $avatar = 'no_image.png';
        } else {
            $avatarUrl = config('constants.baseUrl') . config('constants.avatar');
            $avatar = 'no_image.png';
        }

        if ($pic != 'NA') {
            if (strpos($pic, 'https') !== false) {
                $picture = $pic;
            } else {
                $picture = $url . $pic;
            }
        } else {
            if ($platform == 'backend') {
                $picture = $avatarUrl . $avatar;
            } elseif ($platform == 'web') {
                $picture = $avatarUrl . $avatar;
            } elseif ($platform == 'app') {
                $picture = $avatarUrl . $avatar;
            } else {
                $picture = 'NA';
            }
        }

        return $picture;
    }

    public function uploadPicture($image, $previousImg, $platform, $imgType)
    {
        try {
            $image = $image;
            $input['imagename'] = md5($image->getClientOriginalName() . microtime()) . "." . $image->getClientOriginalExtension();
            if ($platform == 'backend') {
                if ($imgType == 'adminPic') {
                    $largeWidth = '200';
                    $largeHeight = '200';
                    $largePicPath = config('constants.adminPic');
                    $smallPicPath = '';
                    Image::make($image->getRealPath())->resize($largeWidth, $largeHeight)->save($largePicPath . $input['imagename']);
                } elseif ($imgType == 'bannerPic') {
                    // $largePicPath = config('constants.bannerPic');
                    // $smallPicPath = '';
                    // $image->move($largePicPath, $input['imagename']);

                    $largeWidth = '640';
                    $largeHeight = '240';
                    $largePicPath = config('constants.bannerPic');
                    $smallPicPath = '';
                    Image::make($image->getRealPath())->resize($largeWidth, $largeHeight)->save($largePicPath . $input['imagename']);
                } elseif ($imgType == 'userPic') {
                    $largePicPath = config('constants.userPic');
                    $smallPicPath = '';
                    $image->move($largePicPath, $input['imagename']);
                } elseif ($imgType == 'bigLogoPic') {
                    $largePicPath = config('constants.bigLogoPic');
                    $smallPicPath = '';
                    $image->move($largePicPath, $input['imagename']);
                } elseif ($imgType == 'smallLogoPic') {
                    $largePicPath = config('constants.smallLogoPic');
                    $smallPicPath = '';
                    $image->move($largePicPath, $input['imagename']);
                } elseif ($imgType == 'favIconPic') {
                    $largePicPath = config('constants.favIconPic');
                    $smallPicPath = '';
                    $image->move($largePicPath, $input['imagename']);
                } elseif ($imgType == 'didYouKnowPic') {
                    $largePicPath = config('constants.didYouKnowPic');
                    $smallPicPath = '';
                    $image->move($largePicPath, $input['imagename']);
                } elseif ($imgType == 'enviroVocabularyPic') {
                    $largePicPath = config('constants.enviroVocabularyPic');
                    $smallPicPath = '';
                    $image->move($largePicPath, $input['imagename']);
                } elseif ($imgType == 'freeDownloadsFile') {
                    $largePicPath = config('constants.freeDownloadsFile');
                    $smallPicPath = '';
                    $image->move($largePicPath, $input['imagename']);
                } elseif ($imgType == 'pledgeFile') {
                    $largePicPath = config('constants.pledgeFile');
                    $smallPicPath = '';
                    $image->move($largePicPath, $input['imagename']);
                } elseif ($imgType == 'freeDownloadPic') {
                    $largePicPath = config('constants.freeDownloadPic');
                    $smallPicPath = '';
                    $image->move($largePicPath, $input['imagename']);
                } elseif ($imgType == 'journalPic') {
                    $largePicPath = config('constants.journalPic');
                    $smallPicPath = '';
                    $image->move($largePicPath, $input['imagename']);
                } elseif ($imgType == 'taskCompletePic') {
                    $largePicPath = config('constants.taskCompletePic');
                    $smallPicPath = '';
                    $image->move($largePicPath, $input['imagename']);
                } elseif ($imgType == 'taskQuestionPic') {
                    $largePicPath = config('constants.taskQuestionPic');
                    $smallPicPath = '';
                    $image->move($largePicPath, $input['imagename']);
                } else {
                }
            } elseif ($platform == 'web') {
                if ($imgType == 'customerPic') {
                    $largeWidth = '300';
                    $largeHeight = '300';
                    $largePicPath = config('constants.customerPic');
                    $smallPicPath = '';
                    Image::make($image->getRealPath())->resize($largeWidth, $largeHeight)->save($largePicPath . $input['imagename']);
                }
            }

            if (!empty($previousImg)) {
                if ($previousImg == 'NA') {
                    return $input['imagename'];
                } else {
                    if (unlink($largePicPath . $previousImg)) {
                        if ($smallPicPath != '') {
                            unlink($smallPicPath . $previousImg);
                        }
                        return $input['imagename'];
                    } else {
                        return false;
                    }
                }
            } else {
                return $input['imagename'];
            }
        } catch (Exception $e) {
            return false;
        }
    }
}
