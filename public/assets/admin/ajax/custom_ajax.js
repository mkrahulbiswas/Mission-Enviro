$('document').ready(function () {

    var pathArray = window.location.pathname.split('/'),
        submitForm, submitBtn;

    if (location.hostname === "localhost") {
        var baseUrl = "http://localhost/Saheti/admin/";
    } else if (location.hostname === "192.168.0.126") {
        var baseUrl = "http://192.168.0.126/Saheti/admin/";
    } else if (location.hostname === "intelligentappsolutionsdemo.com") {
        var baseUrl = 'http://intelligentappsolutionsdemo.com/current-project/website/Saheti/admin/';
    }




    /*--========================= ( Admin START ) =========================--*/
    //====Save Sub Admin====//
    $("#saveAdminForm").submit(function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,

            beforeSend: function () {
                $("#loader").css("display", 'block');
                $("#saveAdminBtn").attr("disabled", "disabled").find('span').text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                $("#saveAdminBtn").attr("disabled", false).find('span').text('save');

                $("#fileErr").text('');
                $("#nameErr").text('');
                $("#emailErr").text('');
                $("#phoneErr").text('');
                //$("#passwordErr").text('');
                $("#roleErr").text('');
                $("#addressErr").text('');

                if (msg.status == 0) {
                    $("#alert").removeClass("alert-success").addClass("alert-danger");
                    $("#alert").css("display", "block");
                    $("#validationAlert").html(msg.msg);

                    $.each(msg.errors.file, function (i) {
                        $("#fileErr").text(msg.errors.file[i]);
                    });
                    $.each(msg.errors.name, function (i) {
                        $("#nameErr").text(msg.errors.name[i]);
                    });
                    $.each(msg.errors.email, function (i) {
                        $("#emailErr").text(msg.errors.email[i]);
                    });
                    $.each(msg.errors.phone, function (i) {
                        $("#phoneErr").text(msg.errors.phone[i]);
                    });
                    $.each(msg.errors.role, function (i) {
                        $("#roleErr").text(msg.errors.role[i]);
                    });
                    $.each(msg.errors.address, function (i) {
                        $("#addressErr").text(msg.errors.address[i]);
                    });
                } else if (msg.status == 1) {
                    $("#alert").removeClass("alert-danger").addClass("alert-success");
                    $("#alert").css("display", "block");
                    $("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        $("#alert").hide();
                    }, 2000);
                    setTimeout(function () {
                        location.reload();
                    }, 3000);

                }
            }
        });
    });

    //====Update Sub Admin====//
    $("#updateAdminForm").submit(function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,

            beforeSend: function () {
                $("#loader").css("display", 'block');
                $("#updateAdminBtn").attr("disabled", "disabled").find('span').text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                $("#updateAdminBtn").attr("disabled", false).find('span').text('Update');

                $("#fileErr").text('');
                $("#nameErr").text('');
                $("#emailErr").text('');
                $("#phoneErr").text('');
                $("#addressErr").text('');

                if (msg.status == 0) {
                    $("#alert").removeClass("alert-success").addClass("alert-danger");
                    $("#alert").css("display", "block");
                    $("#validationAlert").html(msg.msg);

                    $.each(msg.errors.file, function (i) {
                        $("#fileErr").text(msg.errors.file[i]);
                    });
                    $.each(msg.errors.name, function (i) {
                        $("#nameErr").text(msg.errors.name[i]);
                    });
                    $.each(msg.errors.email, function (i) {
                        $("#emailErr").text(msg.errors.email[i]);
                    });
                    $.each(msg.errors.phone, function (i) {
                        $("#phoneErr").text(msg.errors.phone[i]);
                    });
                    $.each(msg.errors.address, function (i) {
                        $("#addressErr").text(msg.errors.address[i]);
                    });
                } else if (msg.status == 1) {
                    $("#alert").removeClass("alert-danger").addClass("alert-success");
                    $("#alert").css("display", "block");
                    $("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        $("#alert").hide();
                    }, 2000);
                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                }
            }
        });
    });

    //====Status / Delete Sub Admin====// 
    $('body').delegate('#admin-listing .action', 'click', function () {
        var type = $(this).attr('data-type'),
            id = $(this).attr('data-id'),
            action = $(this).attr('data-action') + '/' + id,
            adminListing = $('#admin-listing').DataTable();
        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                var res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                var res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        adminListing.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else if (type == 'delete') {
            id = $(this).attr('data-id');
            action = $(this).attr('data-action');
            $.ajax({
                url: action,
                data: {
                    id: id
                },
                type: 'post',
                beforeSend: function () {

                },
                success: function (msg) {
                    if (msg.status == 1) {
                        $('#userDoctorsReload').trigger('click');
                    } else {
                        alert('LOL');
                    }
                }
            });
        } else {

        }
    });



    //---- ( User Save ) ----//
    $("#saveUserForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#saveUserBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: new FormData(this),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.attr("disabled", "disabled").closest('button').find("span").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.attr("disabled", false).closest('button').find("span").text('Save');

                submitForm.find("#imageErr, #nameErr, #schoolErr, #classErr, #cityErr, #mentorNameErr,  #mentorEmailErr, #mentorPhoneErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.image, function (i) {
                        submitForm.find("#imageErr").text(msg.errors.image[i]);
                    });
                    $.each(msg.errors.name, function (i) {
                        submitForm.find("#nameErr").text(msg.errors.name[i]);
                    });
                    $.each(msg.errors.school, function (i) {
                        submitForm.find("#schoolErr").text(msg.errors.school[i]);
                    });
                    $.each(msg.errors.class, function (i) {
                        submitForm.find("#classErr").text(msg.errors.class[i]);
                    });
                    $.each(msg.errors.city, function (i) {
                        submitForm.find("#cityErr").text(msg.errors.city[i]);
                    });
                    $.each(msg.errors.mentorName, function (i) {
                        submitForm.find("#mentorNameErr").text(msg.errors.mentorName[i]);
                    });
                    $.each(msg.errors.mentorEmail, function (i) {
                        submitForm.find("#mentorEmailErr").text(msg.errors.mentorEmail[i]);
                    });
                    $.each(msg.errors.mentorPhone, function (i) {
                        submitForm.find("#mentorPhoneErr").text(msg.errors.mentorPhone[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);

                    submitForm[0].reset();
                    submitForm.find('.dropify-clear').trigger('click')
                }
            }
        });
    });

    //---- ( User Update ) ----//
    $("#updateUserForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#updateUserBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: new FormData(this),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.attr("disabled", "disabled").closest('button').find("span").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.attr("disabled", false).closest('button').find("span").text('Update');

                submitForm.find("#imageErr, #nameErr, #schoolErr, #classErr, #cityErr, #mentorNameErr,  #mentorEmailErr, #mentorPhoneErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.image, function (i) {
                        submitForm.find("#imageErr").text(msg.errors.image[i]);
                    });
                    $.each(msg.errors.name, function (i) {
                        submitForm.find("#nameErr").text(msg.errors.name[i]);
                    });
                    $.each(msg.errors.school, function (i) {
                        submitForm.find("#schoolErr").text(msg.errors.school[i]);
                    });
                    $.each(msg.errors.class, function (i) {
                        submitForm.find("#classErr").text(msg.errors.class[i]);
                    });
                    $.each(msg.errors.city, function (i) {
                        submitForm.find("#cityErr").text(msg.errors.city[i]);
                    });
                    $.each(msg.errors.mentorName, function (i) {
                        submitForm.find("#mentorNameErr").text(msg.errors.mentorName[i]);
                    });
                    $.each(msg.errors.mentorEmail, function (i) {
                        submitForm.find("#mentorEmailErr").text(msg.errors.mentorEmail[i]);
                    });
                    $.each(msg.errors.mentorPhone, function (i) {
                        submitForm.find("#mentorPhoneErr").text(msg.errors.mentorPhone[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);
                }
            }
        });
    });

    //---- ( User Status, Edit ) ----//
    $('body').delegate('#admin-user-listing .actionDatatable', 'click', function () {

        var type = $(this).attr('data-type'),
            res = '',
            reloadDatatble = $('#admin-user-listing').DataTable();

        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: $(this).attr('data-action'),
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else {}
    });
    /*--========================= ( Admin END ) =========================--*/





    /*--========================= ( Dashboard START ) =========================--*/
    //---- ( Referral Point Save ) ----//
    $("#saveReferralPointForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#saveReferralPointBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Save');

                submitForm.find("#usedFromErr, #usedByErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.usedFrom, function (i) {
                        submitForm.find("#usedFromErr").text(msg.errors.usedFrom[i]);
                    });
                    $.each(msg.errors.usedBy, function (i) {
                        submitForm.find("#usedByErr").text(msg.errors.usedBy[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);

                    submitForm[0].reset();
                    $('#dashboard-referral-listing').DataTable().ajax.reload(null, false);
                }
            }
        });
    });

    //---- ( Referral Point Update ) ----//
    $("#updateReferralPointForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#updateReferralPointBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Update');

                submitForm.find("#usedFromErr, #usedByErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.usedFrom, function (i) {
                        submitForm.find("#usedFromErr").text(msg.errors.usedFrom[i]);
                    });
                    $.each(msg.errors.usedBy, function (i) {
                        submitForm.find("#usedByErr").text(msg.errors.usedBy[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);
                    $('#dashboard-referral-listing').DataTable().ajax.reload(null, false);
                }
            }
        });
    });

    //---- ( Referral Point Status, Edit ) ----//
    $('body').delegate('#dashboard-referral-listing .actionDatatable', 'click', function () {

        var type = $(this).attr('data-type'),
            res = '',
            action = $(this).attr('data-action'),
            reloadDatatble = $('#dashboard-referral-listing').DataTable();

        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else if (type == 'delete') {

            res = confirm('Do you really want to delete?');
            if (res === false) {
                return;
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else {
            $('#con-edit-modal').modal('show');
            editData = JSON.parse($(this).attr('data-array'));
            $('#con-edit-modal #usedFrom').val(editData.usedFrom);
            $('#con-edit-modal #usedBy').val(editData.usedBy);
            $('#con-edit-modal #id').val(editData.id);
        }
    });
    /*--========================= ( Dashboard END ) =========================--*/





    /*--========================= ( CMS START ) =========================--*/
    $("#saveBannerForm").submit(function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: $(this).attr('method'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                $("#saveBannerBtn").attr("disabled", "disabled");
                $("#saveBannerBtn span").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                $("#saveBannerBtn").attr("disabled", false);
                $("#saveBannerBtn span").text('Save');
                $("#fileErr").text('');
                $("#adminIdErr").text('');
                $("#choseTestPackageErr").text('');
                $("#testPackageErr").text('');

                if (msg.status == 0) {
                    $("#saveBannerForm").find("#alert").removeClass("alert-success").addClass("alert-danger");
                    $("#saveBannerForm").find("#alert").css("display", "block");
                    $("#saveBannerForm").find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.file, function (i) {
                        $("#fileErr").text(msg.errors.file[i]);
                    });
                    $.each(msg.errors.adminId, function (i) {
                        $("#adminIdErr").text(msg.errors.adminId[i]);
                    });
                    $.each(msg.errors.choseTestPackage, function (i) {
                        $("#choseTestPackageErr").text(msg.errors.choseTestPackage[i]);
                    });
                    $.each(msg.errors.testPackage, function (i) {
                        $("#testPackageErr").text(msg.errors.testPackage[i]);
                    });

                } else {
                    $("#saveBannerForm").find("#alert").removeClass("alert-danger").addClass("alert-success");
                    $("#saveBannerForm").find("#alert").css("display", "block");
                    $("#saveBannerForm").find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        $("#saveBannerForm").find("#alert").css('display', 'none');
                    }, 1000);

                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }
            }
        });
    });

    $("#updateBannerForm").submit(function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: $(this).attr('method'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                $("#updateBannerBtn").attr("disabled", "disabled");
                $("#updateBannerBtn span").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                $("#updateBannerBtn").attr("disabled", false);
                $("#updateBannerBtn span").text('Update');
                $("#fileErr").text('');
                $("#adminIdErr").text('');
                $("#choseTestPackageErr").text('');
                $("#testPackageErr").text('');

                if (msg.status == 0) {
                    $("#updateBannerForm").find("#alert").removeClass("alert-success").addClass("alert-danger");
                    $("#updateBannerForm").find("#alert").css("display", "block");
                    $("#updateBannerForm").find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.file, function (i) {
                        $("#fileErr").text(msg.errors.file[i]);
                    });
                    $.each(msg.errors.adminId, function (i) {
                        $("#adminIdErr").text(msg.errors.adminId[i]);
                    });
                    $.each(msg.errors.choseTestPackage, function (i) {
                        $("#choseTestPackageErr").text(msg.errors.choseTestPackage[i]);
                    });
                    $.each(msg.errors.testPackage, function (i) {
                        $("#testPackageErr").text(msg.errors.testPackage[i]);
                    });

                } else {
                    $("#updateBannerForm").find("#alert").removeClass("alert-danger").addClass("alert-success");
                    $("#updateBannerForm").find("#alert").css("display", "block");
                    $("#updateBannerForm").find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        $("#updateBannerForm").find("#alert").css('display', 'none');
                    }, 2000);

                    // setTimeout(function () {
                    //     location.reload();
                    // }, 2000);
                }
            }
        });
    });

    $('body').delegate('#cms-banner-listing .action', 'click', function () {
        var type = $(this).attr('data-type'),
            id = $(this).attr('data-id'),
            res = '',
            action = $(this).attr('data-action') + '/' + id,
            bannerDatatable = $('#cms-banner-listing').DataTable();
        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);


                        bannerDatatable.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else if (type == 'delete') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        bannerDatatable.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else if (type == 'edit') {

        } else {

        }
    });
    /*--========================= ( CMS END ) =========================--*/





    /*--========================= ( Infographics Management START ) =========================--*/
    //---- ( Enviro Vocabulary Save ) ----//
    $("#saveEnviroVocabularyForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#saveEnviroVocabularyBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Save');

                submitForm.find("#imageErr, #titleErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.image, function (i) {
                        submitForm.find("#imageErr").text(msg.errors.image[i]);
                    });
                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);

                    submitForm.find('.dropify-clear').trigger('click');
                    submitForm[0].reset();
                    // $(this).find('select').val([0]).trigger('change');
                }
            }
        });
    });

    //---- ( Enviro Vocabulary Update ) ----//
    $("#updateEnviroVocabularyForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#updateEnviroVocabularyBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Update');

                submitForm.find("#imageErr, #titleErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.image, function (i) {
                        submitForm.find("#imageErr").text(msg.errors.image[i]);
                    });
                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);
                }
            }
        });
    });

    //---- ( Enviro Vocabulary Status, Edit ) ----//
    $('body').delegate('#infographics-enviroVocabulary-listing .actionDatatable', 'click', function () {

        var type = $(this).attr('data-type'),
            res = '',
            action = $(this).attr('data-action'),
            reloadDatatble = $('#infographics-enviroVocabulary-listing').DataTable();

        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else if (type == 'delete') {

            res = confirm('Do you really want to delete?');
            if (res === false) {
                return;
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else {

        }
    });




    //---- ( Did You Know Save ) ----//
    $("#saveDidYouKnowForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#saveDidYouKnowBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Save');

                submitForm.find("#imageErr, #titleErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.image, function (i) {
                        submitForm.find("#imageErr").text(msg.errors.image[i]);
                    });
                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);

                    submitForm.find('.dropify-clear').trigger('click');
                    submitForm[0].reset();
                }
            }
        });
    });

    //---- ( Did You Know Update ) ----//
    $("#updateDidYouKnowForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#updateDidYouKnowBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Update');

                submitForm.find("#imageErr, #titleErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.image, function (i) {
                        submitForm.find("#imageErr").text(msg.errors.image[i]);
                    });
                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);
                }
            }
        });
    });

    //---- ( Did You Know Status, Edit ) ----//
    $('body').delegate('#infographics-didYouKnow-listing .actionDatatable', 'click', function () {

        var type = $(this).attr('data-type'),
            res = '',
            action = $(this).attr('data-action'),
            reloadDatatble = $('#infographics-didYouKnow-listing').DataTable();

        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                }
            });
        } else if (type == 'delete') {

            res = confirm('Do you really want to delete?');
            if (res === false) {
                return;
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else {

        }
    });
    /*--========================= ( Infographics Management END ) =========================--*/





    /*--========================= ( Free- Downloads START ) =========================--*/
    //---- ( Free Downloads Save ) ----//
    $("#saveFreeDownloadsForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#saveFreeDownloadsBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Save');

                submitForm.find("#fileErr, #titleErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.file, function (i) {
                        submitForm.find("#fileErr").text(msg.errors.file[i]);
                    });
                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);

                    submitForm.find('.dropify-clear').trigger('click');
                    submitForm[0].reset();
                    // $(this).find('select').val([0]).trigger('change');
                }
            }
        });
    });

    //---- ( Free Downloads Update ) ----//
    $("#updateFreeDownloadsForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#updateFreeDownloadsBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Update');

                submitForm.find("#fileErr, #titleErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.file, function (i) {
                        submitForm.find("#fileErr").text(msg.errors.file[i]);
                    });
                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);
                }
            }
        });
    });

    //---- ( Free Downloads Status, Edit ) ----//
    $('body').delegate('#freeDownloads-listing .actionDatatable', 'click', function () {

        var type = $(this).attr('data-type'),
            res = '',
            action = $(this).attr('data-action'),
            reloadDatatble = $('#freeDownloads-listing').DataTable();

        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else if (type == 'delete') {

            res = confirm('Do you really want to delete?');
            if (res === false) {
                return;
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else {

        }
    });
    /*--========================= ( Free- Downloads END ) =========================--*/





    /*--========================= ( Journal Management START ) =========================--*/
    //---- ( Requested Status, Delete ) ----//
    $('body').delegate('#journalManagement-requested-listing .actionDatatable', 'click', function () {

        var type = $(this).attr('data-type'),
            res = '',
            action = $(this).attr('data-action'),
            reloadDatatble = $('#journalManagement-requested-listing').DataTable();

        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else if (type == 'delete') {

            res = confirm('Do you really want to delete?');
            if (res === false) {
                return;
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else {

        }
    });

    //---- ( Accepted Status, Delete ) ----//
    $('body').delegate('#journalManagement-accepted-listing .actionDatatable', 'click', function () {

        var type = $(this).attr('data-type'),
            res = '',
            action = $(this).attr('data-action'),
            reloadDatatble = $('#journalManagement-accepted-listing').DataTable();

        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else if (type == 'delete') {

            res = confirm('Do you really want to delete?');
            if (res === false) {
                return;
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else {

        }
    });
    /*--========================= ( Journal Management END ) =========================--*/





    /*--========================= ( Video START ) =========================--*/
    //---- ( Video Save ) ----//
    $("#saveVideoForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#saveVideoBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: $(this).attr('method'),
            dataType: 'json',
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Save');

                submitForm.find("#linkErr, #titleErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.link, function (i) {
                        submitForm.find("#linkErr").text(msg.errors.link[i]);
                    });
                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);

                    submitForm[0].reset();
                }
            }
        });
    });

    //---- ( Video Update ) ----//
    $("#updateVideoForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#updateVideoBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: $(this).attr('method'),
            dataType: 'json',
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Update');

                submitForm.find("#linkErr, #titleErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.link, function (i) {
                        submitForm.find("#linkErr").text(msg.errors.link[i]);
                    });
                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);
                }
            }
        });
    });

    //---- ( Video Status, Edit ) ----//
    $('body').delegate('#video-listing .actionDatatable', 'click', function () {

        var type = $(this).attr('data-type'),
            res = '',
            action = $(this).attr('data-action'),
            reloadDatatble = $('#video-listing').DataTable();

        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else if (type == 'delete') {

            res = confirm('Do you really want to delete?');
            if (res === false) {
                return;
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else {}
    });
    /*--========================= ( Video END ) =========================--*/





    /*--========================= ( Task Management START ) =========================--*/
    //---- ( Manage Task Level Save ) ----//
    $("#saveManageTaskLevelForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#saveManageTaskLevelBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: $(this).attr('method'),
            dataType: 'json',
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Save');

                submitForm.find("#titleErr, #dateFromErr, #dateToErr, #pointErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.dateFrom, function (i) {
                        submitForm.find("#dateFromErr").text(msg.errors.dateFrom[i]);
                    });
                    $.each(msg.errors.dateTo, function (i) {
                        submitForm.find("#dateToErr").text(msg.errors.dateTo[i]);
                    });
                    $.each(msg.errors.point, function (i) {
                        submitForm.find("#pointErr").text(msg.errors.point[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);

                    submitForm[0].reset();
                    submitForm.find('.date-picker').datepicker('update', '');
                }
            }
        });
    });

    //---- ( Manage Task Level Update ) ----//
    $("#updateManageTaskLevelForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#updateManageTaskLevelBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: $(this).attr('method'),
            dataType: 'json',
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Update');

                submitForm.find("#titleErr, #dateFromErr, #dateToErr, #pointErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.dateFrom, function (i) {
                        submitForm.find("#dateFromErr").text(msg.errors.dateFrom[i]);
                    });
                    $.each(msg.errors.dateTo, function (i) {
                        submitForm.find("#dateToErr").text(msg.errors.dateTo[i]);
                    });
                    $.each(msg.errors.point, function (i) {
                        submitForm.find("#pointErr").text(msg.errors.point[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);
                }
            }
        });
    });

    //---- ( Manage Task Level Status, Edit ) ----//
    $('body').delegate('#taskManagement-manageTaskLevel-listing .actionDatatable', 'click', function () {

        var type = $(this).attr('data-type'),
            res = '',
            action = $(this).attr('data-action'),
            reloadDatatble = $('#taskManagement-manageTaskLevel-listing').DataTable();

        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else if (type == 'delete') {

            res = confirm('Do you really want to delete?');
            if (res === false) {
                return;
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else {}
    });



    //---- ( Manage Task Quarter Save ) ----//
    $("#saveManageTaskQuarterForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#saveManageTaskQuarterBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: $(this).attr('method'),
            dataType: 'json',
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Save');

                submitForm.find("#taskLevelErr, #rankPointErr, #titleErr, #dateFromErr, #dateToErr, #pointErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.taskLevel, function (i) {
                        submitForm.find("#taskLevelErr").text(msg.errors.taskLevel[i]);
                    });
                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.dateFrom, function (i) {
                        submitForm.find("#dateFromErr").text(msg.errors.dateFrom[i]);
                    });
                    $.each(msg.errors.dateTo, function (i) {
                        submitForm.find("#dateToErr").text(msg.errors.dateTo[i]);
                    });
                    $.each(msg.errors.rankPoint, function (i) {
                        submitForm.find("#rankPointErr").text(msg.errors.rankPoint[i]);
                    });
                    $.each(msg.errors.point, function (i) {
                        submitForm.find("#pointErr").text(msg.errors.point[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);

                    submitForm[0].reset();
                    submitForm.find('select').val([0]).trigger('change');
                    submitForm.find('.date-picker').datepicker('update', '');
                }
            }
        });
    });

    //---- ( Manage Task Quarter Update ) ----//
    $("#updateManageTaskQuarterForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#updateManageTaskQuarterBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serializeArray(),
            type: $(this).attr('method'),
            dataType: 'json',
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Update');

                submitForm.find("#taskLevelErr, #rankPointErr, #titleErr, #dateFromErr, #dateToErr, #pointErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.taskLevel, function (i) {
                        submitForm.find("#taskLevelErr").text(msg.errors.taskLevel[i]);
                    });
                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.dateFrom, function (i) {
                        submitForm.find("#dateFromErr").text(msg.errors.dateFrom[i]);
                    });
                    $.each(msg.errors.dateTo, function (i) {
                        submitForm.find("#dateToErr").text(msg.errors.dateTo[i]);
                    });
                    $.each(msg.errors.rankPoint, function (i) {
                        submitForm.find("#rankPointErr").text(msg.errors.rankPoint[i]);
                    });
                    $.each(msg.errors.point, function (i) {
                        submitForm.find("#pointErr").text(msg.errors.point[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);
                }
            }
        });
    });

    //---- ( Manage Task Quarter Status, Edit ) ----//
    $('body').delegate('#taskManagement-manageTaskQuarter-listing .actionDatatable', 'click', function () {

        var type = $(this).attr('data-type'),
            res = '',
            action = $(this).attr('data-action'),
            reloadDatatble = $('#taskManagement-manageTaskQuarter-listing').DataTable();

        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else if (type == 'delete') {

            res = confirm('Do you really want to delete?');
            if (res === false) {
                return;
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else {}
    });



    //---- ( Manage Task Question Save ) ----//
    $("#saveManageTasksForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#saveManageTasksBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Save');

                submitForm.find("#imageErr, #taskLevelErr, #taskQuarterErr, #levelErr, #dateErr, #pointErr, #titleErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.image, function (i) {
                        submitForm.find("#imageErr").text(msg.errors.image[i]);
                    });
                    $.each(msg.errors.taskLevel, function (i) {
                        submitForm.find("#taskLevelErr").text(msg.errors.taskLevel[i]);
                    });
                    $.each(msg.errors.taskQuarter, function (i) {
                        submitForm.find("#taskQuarterErr").text(msg.errors.taskQuarter[i]);
                    });
                    $.each(msg.errors.level, function (i) {
                        submitForm.find("#levelErr").text(msg.errors.level[i]);
                    });
                    $.each(msg.errors.date, function (i) {
                        submitForm.find("#dateErr").text(msg.errors.date[i]);
                    });
                    $.each(msg.errors.point, function (i) {
                        submitForm.find("#pointErr").text(msg.errors.point[i]);
                    });
                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);

                    submitForm[0].reset();
                    submitForm.find('.dropify-clear').trigger('click');
                    submitForm.find('select').val([0]).trigger('change');
                    submitForm.find('.date-picker').datepicker('update', '');
                }
            }
        });
    });

    //---- ( Manage Task Question Update ) ----//
    $("#updateManageTasksForm").submit(function (event) {

        submitForm = $(this);
        submitBtn = $(this).find('#updateManageTasksBtn');

        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.find("span").attr("disabled", "disabled").text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.find("span").attr("disabled", false).text('Update');

                submitForm.find("#imageErr, #taskLevelErr, #taskQuarterErr, #levelErr, #dateErr, #pointErr, #titleErr, #descriptionErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.image, function (i) {
                        submitForm.find("#imageErr").text(msg.errors.image[i]);
                    });
                    $.each(msg.errors.taskLevel, function (i) {
                        submitForm.find("#taskLevelErr").text(msg.errors.taskLevel[i]);
                    });
                    $.each(msg.errors.taskQuarter, function (i) {
                        submitForm.find("#taskQuarterErr").text(msg.errors.taskQuarter[i]);
                    });
                    $.each(msg.errors.level, function (i) {
                        submitForm.find("#levelErr").text(msg.errors.level[i]);
                    });
                    $.each(msg.errors.date, function (i) {
                        submitForm.find("#dateErr").text(msg.errors.date[i]);
                    });
                    $.each(msg.errors.point, function (i) {
                        submitForm.find("#pointErr").text(msg.errors.point[i]);
                    });
                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.description, function (i) {
                        submitForm.find("#descriptionErr").text(msg.errors.description[i]);
                    });
                } else {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    setTimeout(function () {
                        submitForm.find("#alert").css('display', 'none');
                    }, 1500);
                }
            }
        });
    });

    //---- ( Manage Task Question Status, Edit ) ----//
    $('body').delegate('#taskManagement-manageTasks-listing .actionDatatable', 'click', function () {

        var type = $(this).attr('data-type'),
            res = '',
            action = $(this).attr('data-action'),
            reloadDatatble = $('#taskManagement-manageTasks-listing').DataTable();

        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to block?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to unblock?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else if (type == 'delete') {

            res = confirm('Do you really want to delete?');
            if (res === false) {
                return;
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else {}
    });



    //---- ( Request Status, Edit ) ----//
    $('body').delegate('#taskManagement-taskRequests-listing .actionDatatable', 'click', function () {

        var type = $(this).attr('data-type'),
            res = '',
            action = $(this).attr('data-action'),
            reloadDatatble = $('#taskManagement-taskRequests-listing').DataTable();

        if (type == 'status') {

            if ($(this).attr('data-status') == 'block') {
                res = confirm('Do you really want to Approve?');
                if (res === false) {
                    return;
                }
            } else {
                res = confirm('Do you really want to Reject?');
                if (res === false) {
                    return;
                }
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        reloadDatatble.ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else {}
    });
    /*--========================= ( Task Management END ) =========================--*/






    /*--========================= ( Notification START ) =========================--*/
    //----Send Notification Add----//
    $("#saveSendNotificationForm").submit(function (event) {
        event.preventDefault();
        submitForm = $(this);
        submitBtn = $(this).find('#saveSendNotificationBtn');

        $.ajax({
            url: $(this).attr('action'),
            data: new FormData(this),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,

            beforeSend: function () {
                $("#loader").css("display", 'block');
                submitBtn.attr("disabled", "disabled").find('span').text('Please wait...');
            },
            success: function (msg) {
                $("#loader").css("display", 'none');
                submitBtn.attr("disabled", false).find('span').text('Save');

                submitForm.find("#champLevelErr, #sendToErr, #titleErr, #messageErr").text('');

                if (msg.status == 0) {
                    submitForm.find("#alert").removeClass("alert-success").addClass("alert-danger");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    $.each(msg.errors.champLevel, function (i) {
                        submitForm.find("#champLevelErr").text(msg.errors.champLevel[i]);
                    });
                    $.each(msg.errors.sendTo, function (i) {
                        submitForm.find("#sendToErr").text(msg.errors.sendTo[i]);
                    });
                    $.each(msg.errors.title, function (i) {
                        submitForm.find("#titleErr").text(msg.errors.title[i]);
                    });
                    $.each(msg.errors.message, function (i) {
                        submitForm.find("#messageErr").text(msg.errors.message[i]);
                    });

                } else if (msg.status == 1) {
                    submitForm.find("#alert").removeClass("alert-danger").addClass("alert-success");
                    submitForm.find("#alert").css("display", "block");
                    submitForm.find("#validationAlert").html(msg.msg);

                    submitForm[0].reset();
                    submitForm.find('select').val(['']).trigger('change');
                    $('#sendNotification-listing').DataTable().ajax.reload(null, false);

                    setTimeout(function () {
                        submitForm.find("#alert").hide();
                    }, 2000);
                }
            }
        });
    });

    //----Notifgication Delete, Detail----//
    $('body').delegate('#sendNotification-listing .actionDatatable', 'click', function () {
        var type = $(this).attr('data-type'),
            id = $(this).attr('data-id'),
            html = '',
            res = '',
            action = $(this).attr('data-action'),
            reloadDatatable = $('#sendNotification-listing').DataTable();
        if (type == 'status') {
            //
        } else if (type == 'delete') {
            res = confirm('Do yoy really want to delete?');
            if (res === false) {
                return;
            }

            $.ajax({
                url: action,
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#alert").removeClass("alert-success").addClass("alert-danger");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);
                    } else {
                        $("#alert").removeClass("alert-danger").addClass("alert-success");
                        $("#alert").css("display", "block");
                        $("#validationAlert").html(msg.msg);

                        $('#sendNotification-listing').ajax.reload(null, false);
                    }
                    setTimeout(function () {
                        $("#alert").css('display', 'none');
                    }, 5000);
                }
            });
        } else if (type == 'edit') {
            //
        } else {
            id = $('#con-detail-modal');
            id.modal('show');
            dataArray = JSON.parse($(this).attr('data-array'));
            console.log(dataArray);
            if (dataArray.sendTo == 1) {
                id.find('#sendTo').text("Notification of '" + ((dataArray.champLevel == 'NA') ? "ALL" : dataArray.champLevel) + "' type user, send to 'ALL' user's");
                id.find('#userName').text('');
            } else {
                id.find('#sendTo').text("Notification of '" + ((dataArray.champLevel == 'NA') ? "ALL" : dataArray.champLevel) + "' type user, send to 'SELECTED' user's");
                html += '<div style="display: flex; flex-direction: row; flex-wrap: wrap;">';
                $.each(dataArray.users, function (i) {
                    html += '<span style="padding: 5px 10px; margin: 5px; box-shadow: 0 5px 5px lightgray;">' + dataArray.users[i].name + '</span>';
                });
                html += '</div>';
                id.find('#userName').html(html);
            }
            id.find('#title span').text(dataArray.title);
            id.find('#message span').text(dataArray.message);
        }
    });
    /*--========================= ( Notification END ) =========================--*/

});
