$(document).ready(function () {

    // if (location.hostname === "localhost") {
    //     var baseUrl = "http://localhost/Saheti/admin/";
    // } else if (location.hostname === "192.168.0.125") {
    //     var baseUrl = "http://192.168.0.125/Saheti/admin/";
    // } else if (location.hostname === "intelligentappsolutionsdemo.com") {
    //     var baseUrl = 'http://intelligentappsolutionsdemo.com/current-project/website/Saheti/admin/';
    // }

    var filter;

    // Responsive Datatable
    $('#responsive-datatable').DataTable();
    $('#responsive-datatable-a').DataTable({
        "serverSide": false,
        searching: false,
        paging: false,
        info: false,
    });



    /*--========================= ( USER START ) =========================--*/
    /*------( Users Admin Listing )--------*/
    $('#admin-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "sub-admins/ajaxGetSubAdmins",

        "columns": [{
                "data": "count",
                "name": "count"
            },
            {
                "data": "name",
                "name": "name"
            },
            {
                "data": "email"
            },
            {
                "data": "phone"
            },
            {
                "data": "role_id",
                "render": function (data, type, row) {
                    if (data == '1') {
                        return 'Super Admin';
                    } else if (data == '2') {
                        return '<span class="label label-primary">Sub Admin</span>';
                    } else if (data == '3') {
                        return '<span class="label label-warning">DC Admin</span>';
                    }
                }
            },
            {
                "data": "status",
                "render": function (data, type, row) {
                    if (data == '0') {
                        return '<span class="label label-danger">Blocked</span>';
                    } else if (data == '1') {
                        return '<span class="label label-success">Active</span>';
                    }
                }
            },
            {
                "data": "profilePic",
                "name": "profilePic",
                "render": function (data, type, row) {
                    return '<img src="' + data + '" class="img-fluid rounded" width="100"/>';
                }
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            },

            {
                "data": "address"
            },
        ]
    });

    /*------( User Listing )------*/
    $('#admin-user-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "user/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "image"
            },
            {
                "data": "name"
            },
            {
                "data": "email"
            },
            {
                "data": "phone"
            },
            {
                "data": "status"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });
    /*--========================= ( USER END ) =========================--*/





    /*--========================= ( Dashboard START ) =========================--*/
    /*------( Referral Point Listing )------*/
    $('#dashboard-referral-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "dashboard/referral-point/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name",
        },
        searching: false,
        paging: false,
        info: false,
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "usedFrom"
            },
            {
                "data": "usedBy"
            },
            {
                "data": "status"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });


    $('#responsive-a-datatable').DataTable({
        searching: false,
        paging: false,
        info: false,
    });
    /*--========================= ( Dashboard END ) =========================--*/





    /*--========================= ( School Management START ) =========================--*/
    /*------( Manage School Listing )------*/
    $('#schoolManagement-manageSchool-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "manage-school/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "schoolName"
            },
            {
                "data": "status"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });

    /*------( Manage School Listing )------*/
    $('#schoolManagement-manageClass-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "manage-class/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "schoolName"
            },
            {
                "data": "class"
            },
            {
                "data": "status"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });
    /*--========================= ( School Management END ) =========================--*/





    /*--========================= ( Info Graphics START ) =========================--*/
    /*------( Fun Facts Listing )------*/
    $('#infographics-enviroVocabulary-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "enviro-vocabulary/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "image"
            },
            {
                "data": "title"
            },
            {
                "data": "description"
            },
            {
                "data": "status"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });

    /*------( Did You Know Listing )------*/
    $('#infographics-didYouKnow-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "did-you-know/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "image"
            },
            {
                "data": "title"
            },
            {
                "data": "description"
            },
            {
                "data": "status"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });
    /*--========================= ( Info Graphics END ) =========================--*/





    /*--========================= ( Free Downloads START ) =========================--*/
    /*------( Free Downloads Listing )------*/
    $('#freeDownloads-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "free-downloads/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "file"
            },
            {
                "data": "title"
            },
            {
                "data": "description"
            },
            {
                "data": "status"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });
    /*--========================= ( Free Downloads END ) =========================--*/





    /*--========================= ( Journal Management START ) =========================--*/
    /*------( Requested Listing )------*/
    $('#journalManagement-requested-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "requested-list/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "user"
            },
            {
                "data": "image"
            },
            {
                "data": "title"
            },
            {
                "data": "description"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });

    /*------( Accepted Listing )------*/
    $('#journalManagement-accepted-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "accepted-list/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "user"
            },
            {
                "data": "image"
            },
            {
                "data": "title"
            },
            {
                "data": "description"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });
    /*--========================= ( Journal Management END ) =========================--*/





    /*--========================= ( Video START ) =========================--*/
    /*------( Video Listing )------*/
    $('#video-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "video/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "title"
            },
            {
                "data": "link"
            },
            {
                "data": "status"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });
    /*--========================= ( Video END ) =========================--*/





    /*--========================= ( Task Management START ) =========================--*/
    /*------( Manage Task Level Listing )------*/
    var manageTaskLevel = $('#taskManagement-manageTaskLevel-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "manage-task-level/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "title"
            },
            {
                "data": "taskLevel"
            },
            {
                "data": "description"
            },
            {
                "data": "status"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });

    $('#filterManageTaskLevelForm').find('#yearFilter, .filterManageTaskLevelBtn').on('change click', function () {
        var formId = $('#filterManageTaskLevelForm'),
            year = $("#yearFilter").val(),
            action = $(this).closest('form').attr('action').split('/'),
            newUrl = action[action.length - 2] + '/' + action[action.length - 1] + "?year=" + year;
        if ($(this).attr('title') == 'Reload') {
            $(this).closest(formId).find("#yearFilter").val(['']).trigger('change');
            newUrl = action[action.length - 2] + '/' + action[action.length - 1] + "?year=" + '';
            manageTaskLevel.ajax.url(newUrl).load();
        } else if ($(this).attr('title') == 'Search') {
            manageTaskLevel.ajax.url(newUrl).load();
        } else {
            manageTaskLevel.ajax.url(newUrl).load();
        }
    });

    /*------( Manage Task Quarter Listing )------*/
    var manageTaskQuarter = $('#taskManagement-manageTaskQuarter-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "manage-task-quarter/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "taskLevel"
            },
            {
                "data": "title"
            },
            {
                "data": "quarterDate"
            },
            {
                "data": "status"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });

    $('#filterManageTaskQuarterForm').find('#taskLevelFilter, .filterManageTaskQuarterBtn').on('change click', function () {
        var formId = $('#filterManageTaskQuarterForm'),
            taskLevel = $("#taskLevelFilter").val(),
            action = $(this).closest('form').attr('action').split('/'),
            newUrl = action[action.length - 2] + '/' + action[action.length - 1] + "?taskLevel=" + taskLevel;
        if ($(this).attr('title') == 'Reload') {
            $(this).closest(formId).find("#taskLevelFilter").val(['']).trigger('change');
            newUrl = action[action.length - 2] + '/' + action[action.length - 1] + "?taskLevel=" + '';
            manageTaskQuarter.ajax.url(newUrl).load();
        } else if ($(this).attr('title') == 'Search') {
            manageTaskQuarter.ajax.url(newUrl).load();
        } else {
            manageTaskQuarter.ajax.url(newUrl).load();
        }
    });

    /*------( Manage Task Listing )------*/
    var manageTasks = $('#taskManagement-manageTasks-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "manage-tasks/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "title"
            },
            {
                "data": "taskLevel"
            },
            {
                "data": "taskQuarter"
            },
            {
                "data": "level"
            },
            // {
            //     "data": "date"
            // },
            {
                "data": "status"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });

    $('#filterManageTasksForm').find('#taskLevelFilter, #taskQuarterFilter, #levelFilter, .filterManageTasksBtn').on('change click', function () {
        var formId = $('#filterManageTasksForm'),
            taskLevel = ($("#taskLevelFilter").val() == null) ? '' : $("#taskLevelFilter").val(),
            taskQuarter = ($("#taskQuarterFilter").val() == null) ? '' : $("#taskQuarterFilter").val(),
            level = ($("#levelFilter").val() == null) ? '' : $("#levelFilter").val(),
            action = $(this).closest('form').attr('action').split('/'),
            newUrl = action[action.length - 2] + '/' + action[action.length - 1] + "?taskLevel=" + taskLevel + "&taskQuarter=" + taskQuarter + "&level=" + level;
        if ($(this).attr('title') == 'Reload') {
            $(this).closest(formId).find("#taskLevelFilter, #taskQuarterFilter, #levelFilter").val(['']).trigger('change');
            newUrl = action[action.length - 2] + '/' + action[action.length - 1] + "?taskLevel=" + '' + "&taskQuarter=" + '' + "&level=" + '';
            manageTasks.ajax.url(newUrl).load();
        } else if ($(this).attr('title') == 'Search') {
            manageTasks.ajax.url(newUrl).load();
        } else {
            manageTasks.ajax.url(newUrl).load();
        }
    });

    /*------( Manage Task Listing )------*/
    var taskRequests = $('#taskManagement-taskRequests-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "task-requests/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "userInfo"
            },
            {
                "data": "taskInfo"
            },
            {
                "data": "requestAt"
            },
            {
                "data": "status"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });

    $('#filterTaskRequestsForm').find('#taskLevelFilter, #taskQuarterFilter, #levelFilter, #statusFilter, .filterTaskRequestsBtn').on('change click', function () {
        var formId = $('#filterTaskRequestsForm'),

            taskLevel = ($("#taskLevelFilter").val() == null) ? '' : $("#taskLevelFilter").val(),
            taskQuarter = ($("#taskQuarterFilter").val() == null) ? '' : $("#taskQuarterFilter").val(),
            level = ($("#levelFilter").val() == null) ? '' : $("#levelFilter").val(),
            status = ($("#statusFilter").val() == null) ? '' : $("#statusFilter").val(),

            action = $(this).closest('form').attr('action').split('/'),
            newUrl = action[action.length - 2] + '/' + action[action.length - 1] + "?taskLevel=" + taskLevel + "&taskQuarter=" + taskQuarter + "&level=" + level + "&status=" + status;
        if ($(this).attr('title') == 'Reload') {
            formId.find("#taskLevelFilter, #taskQuarterFilter, #levelFilter").val(['']).trigger('change');
            formId.find("#statusFilter").val(['Pending']).trigger('change');
            newUrl = action[action.length - 2] + '/' + action[action.length - 1] + "?taskLevel=" + '' + "&taskQuarter=" + '' + "&level=" + '' + "&status=" + '';
            taskRequests.ajax.url(newUrl).load();
        } else if ($(this).attr('title') == 'Search') {
            taskRequests.ajax.url(newUrl).load();
        } else {
            taskRequests.ajax.url(newUrl).load();
        }
    });
    /*--========================= ( Task Management END ) =========================--*/




    /*--========================= ( Score Board START ) =========================--*/
    /*------( Top Ranked )------*/
    var topRanked = $('#leaderBoard-topRanked-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "top-ranked/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                data: "name"
            },
            {
                data: "class"
            },
            {
                data: "taskLevel"
            },
            {
                data: "taskQuarter"
            },
            {
                data: "level"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });

    $('#filterTopRankedForm').find('#levelFilter, .filterTopRankedBtn').on('change click', function () {
        var formId = $('#filterTopRankedForm'),

            taskLevel = $("#taskLevelFilter").val(),
            taskQuarter = $("#taskQuarterFilter").val(),
            level = $("#levelFilter").val(),

            action = $(this).closest('form').attr('action').split('/'),
            newUrl = action[action.length - 2] + '/' + action[action.length - 1] + "?taskLevel=" + taskLevel + "&taskQuarter=" + taskQuarter + "&level=" + level;

        if ($(this).attr('title') == 'Download') {
            returnBack = validateFilterForm(formId, taskLevel, taskQuarter, level, $(this).attr('title'));
            (returnBack == false) ? '' : topRanked.ajax.url(newUrl).load();
        } else if ($(this).attr('title') == 'Reload') {
            formId.find("#taskLevelFilter, #taskQuarterFilter, #levelFilter").val([]).trigger('change');
            newUrl = action[action.length - 2] + '/' + action[action.length - 1] + "?taskLevel=" + '' + "&taskQuarter=" + '' + "&level=" + '';
            topRanked.ajax.url(newUrl).load();
        } else if ($(this).attr('title') == 'Search') {
            returnBack = validateFilterForm(formId, taskLevel, taskQuarter, level, $(this).attr('title'));
            (returnBack == false) ? '' : topRanked.ajax.url(newUrl).load();
        } else {
            returnBack = validateFilterForm(formId, taskLevel, taskQuarter, level, $(this).attr('title'));
            (returnBack == false) ? '' : topRanked.ajax.url(newUrl).load();
        }
    });


    function validateFilterForm(formId, taskLevel, taskQuarter, level, type) {
        if (taskLevel == null || taskQuarter == null || level == null) {
            formId.find("#alert").removeClass("alert-success").addClass("alert-danger");
            formId.find("#alert").css("display", "block");
            formId.find("#validationAlert").html('Must select all the filter value for ' + type);
            return false;
        } else {
            formId.find("#alert").removeClass("alert-danger").addClass("alert-success");
            formId.find("#alert").css("display", "none");
            formId.find("#validationAlert").html('');
            return true;
        }
    }


    /*------( Over All Point Detail )------*/
    var overAllPoint = $('#scoreBoard-overAllPoint-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "over-all-point/ajaxGetList",
        "language": {
            "searchPlaceholder": "Name"
        },
        searching: false,
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                data: "name",
                orderable: false,
            },
            {
                data: "taskLevel",
                orderable: false,
            },
            {
                data: "taskQuarter",
                orderable: false,
            },
            {
                data: "champLevel",
                orderable: false,
            },
            {
                data: "point",
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });

    $('#filterOverAllPointForm').find('#taskLevelFilter, #taskQuarterFilter, #champLevelFilter, #userFilter, .filterOverAllPointBtn').on('change click', function () {
        var formId = $('#filterOverAllPointForm'),

            taskLevel = ($("#taskLevelFilter").val() == '' || $("#taskLevelFilter").val() == null) ? '' : $("#taskLevelFilter").val(),
            taskQuarter = ($("#taskQuarterFilter").val() == '' || $("#taskQuarterFilter").val() == null) ? '' : $("#taskQuarterFilter").val(),
            champLevel = ($("#champLevelFilter").val() == '' || $("#champLevelFilter").val() == null) ? '' : $("#champLevelFilter").val(),
            user = ($("#userFilter").val() == '' || $("#userFilter").val() == null) ? '' : $("#userFilter").val(),

            action = $(this).closest('form').attr('action').split('/'),
            newUrl = action[action.length - 2] + '/' + action[action.length - 1] + "?taskLevel=" + taskLevel + "&taskQuarter=" + taskQuarter + "&champLevel=" + champLevel + "&user=" + user;
        if ($(this).attr('title') == 'Reload') {
            formId.find("#taskLevelFilter, #taskQuarterFilter, #champLevelFilter, #userFilter").val(['']).trigger('change');
            newUrl = action[action.length - 2] + '/' + action[action.length - 1] + "?taskLevel=" + '' + "&taskQuarter=" + '' + "&champLevel=" + '' + "&user=" + '';
            overAllPoint.ajax.url(newUrl).load();
        } else if ($(this).attr('title') == 'Search') {
            overAllPoint.ajax.url(newUrl).load();
        } else {
            overAllPoint.ajax.url(newUrl).load();
        }

    });
    /*--========================= ( Score Board END ) =========================--*/





    /*--========================= ( CMS START ) =========================--*/
    /*------( Banner Listing )------*/
    $('#cms-banner-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "banner/ajaxGetList",
        "columns": [{
                "data": "count",
                "name": "count"
            },
            {
                "data": "image",
                "name": "image",
                "render": function (data, type, row) {
                    return '<img src="' + data + '" class="img-fluid rounded" width="100"/>';
                }
            },
            {
                "data": "dcName"
            },
            {
                "data": "page"
            },
            {
                "data": "bannerFor"
            },
            {
                "data": "name"
            },
            {
                "data": "status",
                "render": function (data, type, row) {
                    if (data == '0') {
                        return '<span class="label label-danger">Blocked</span>';
                    } else if (data == '1') {
                        return '<span class="label label-success">Active</span>';
                    }
                }
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false,
            }
        ]
    });
    /*--========================= ( CMS END ) =========================--*/






    /*--========================= ( send-notification START ) =========================--*/
    /*------( notification Listing )------*/
    $('#sendNotification-listing').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "send-notification/ajaxGetList",
        "columns": [{
                "data": "DT_RowIndex",
                orderable: false,
                searchable: false
            },
            {
                "data": "title"
            },
            {
                "data": "message",
                name: "message"
            },
            {
                "data": "date"
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false,
            }
        ]
    });
    /*--========================= ( send-notification END ) =========================--*/

});
