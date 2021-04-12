$(document).ready(function () {


    $('#responsive-datatable2').DataTable();


    $('.date-picker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    $('.date-picker-rld').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        endDate: new Date()
    });

    $('.time-picker').timepicker({
        autoclose: true,
    });

    $(".year-picker").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true,
        startDate: new Date()
    });

    $(".date-picker-with-range").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        destroy: true,
    });


    ////////Contact Model/////////
    $("#contact-listing").on("click", ".show_full_msg", function () {
        var subject = $(this).attr('data-subject');
        var msg = $(this).attr('data-msg');

        $('#read-msg-modal').find("#full_subject").text(subject);
        $('#read-msg-modal').find("#full_msg").text(msg);
    });


    /*------SELECT 2------*/
    $('.advance-select-class').select2({
        tags: false,
        placeholder: "Select Class"
    });
    $('.advance-select-taskLevel').select2({
        tags: false,
        placeholder: "Select Task Level"
    });
    $('.advance-select-taskQuarter').select2({
        tags: false,
        placeholder: "Select Task Quarter"
    });
    $('.advance-select-champLevel').select2({
        tags: false,
        placeholder: "Select Champ Level"
    });
    $('.advance-select-year').select2({
        tags: false,
        placeholder: "Select Year"
    });
    $('.advance-select-status').select2({
        tags: false,
        placeholder: "Select Status Type"
    });
    $('.advance-select-user').select2({
        tags: false,
        placeholder: "Select User"
    });
    $('.advance-select-sendTo').select2({
        tags: false,
        placeholder: "Select Send To Type"
    });
    $('.advance-select-users').select2({
        ajax: {
            url: function (params) {
                var champLevel = ($(this).closest('#con-add-modal, #con-edit-modal').find('[name="champLevel"] option:selected').val() == '') ? 0 : $(this).closest('#con-add-modal, #con-edit-modal').find('[name="champLevel"] option:selected').val();
                return $(this).attr('data-action') + '/' + champLevel;
            },
            dataType: 'json',
            delay: 250,
            data: function (params) {
                console.log(params.term);
                return {
                    q: params.term,
                    page: params.page || 1,
                    rows: 10,
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.data,
                    pagination: {
                        more: (params.page * 10) < data.total
                    }
                };
            },
            cache: true
        },
        placeholder: "Select User's",
    });


    $('#con-add-modal [name="champLevel"], #con-edit-modal [name="champLevel"]').on('change', function () {
        $(this).closest('form').find('[name = "sendTo"], #users').val(['']).trigger('change');
    });
    $('#con-add-modal [name="sendTo"], #con-edit-modal [name="sendTo"]').on('change', function () {
        if ($(this).val() == 1 || $(this).val() == '') {
            $(this).closest('form').find('.users').val(['']).trigger('change');
            $(this).closest('form').find('.users').closest('.form-group').fadeOut();
        } else {
            $(this).closest('form').find('.users').closest('.form-group').fadeIn();
        }
    });



    /*-------Add Role--------*/
    $('#AddRole #role_id').change(function () {
        $('#AddRole #role').val($(this).children('option:selected').text());
    });



    /*-------Add Multiple Row Data------*/
    $('.Field .AddRow').click(function () {
        html = '<div class="Field FieldDelete"><input type="text" name="name[]" parsley-trigger="change" required placeholder="Enter Category Name" class="form-control" id="name"><div class="DeleteRow"><i class="md-close" style="font-size: 20px; color:white"></i></div></div>';
        $(this).closest('.form-group').append(html);
    });

    $('.form-group').delegate('.DeleteRow', 'click', function () {
        $(this).closest('.FieldDelete').remove();
    });

    $('.Edit').click(function () {
        $('#EditClick').trigger('click');
        $('#Edit #id').val($(this).closest('tr').attr('data-id'));
        $('#Edit #name').val($(this).closest('tr').find('td:nth-child(2)').attr('data-name'));
        $('#Edit #category').val($(this).closest('tr').find('td:nth-child(3)').attr('data-category'));
    });




    /*-------Logo------*/
    $('.Logo .smallLogo').click(function () {
        $('#smallLogo').trigger('click');
        $('#saveSmallLogoFormAdd #id').val($(this).closest('tr').attr('data-id'));
    });

    $('.Logo .favIcon').click(function () {
        $('#favIcon').trigger('click');
        $('#saveFavIconFormAdd #id').val($(this).closest('tr').attr('data-id'));
    });



    /*------Model Close-------*/
    $('#con-add-modal, #con-edit-modal').on("hidden.bs.modal", function () {
        $(this).find('form')[0].reset();
        $(this).find('.dropify-clear').trigger('click');
        $(this).find('select').val([0]).trigger('change');

        $("#saveReferralPointForm, #updateReferralPointForm").find("#alert").css('display', 'none');
        $('#saveReferralPointForm, #updateReferralPointForm').find("#usedFromErr, #usedByErr").text('');

        $("#saveSendNotificationForm, #updateSendNotificationForm").find("#alert").css('display', 'none');
        $('#saveSendNotificationForm, #updateSendNotificationForm').find("#userTypeErr, #sendToErr, #usersErr, #titleErr, #messageErr").text('');
    });


    /*------Task Quarter, Task Question Chage Set Date Select Limitation-------*/
    $('#saveManageTaskQuarterForm, #updateManageTaskQuarterForm').find('#taskLevel').change(function () {
        $('.date-picker-with-range').val('').datepicker('update');
        $('.date-picker-with-range').datepicker('setStartDate', $(this).children('option:selected').attr('data-dateFrom'));
        $('.date-picker-with-range').datepicker('setEndDate', $(this).children('option:selected').attr('data-dateTo'));
    });

    $('#saveManageTasksForm, #updateManageTasksForm').find('#taskQuarter, #taskLevel').change(function () {
        if ($(this).attr('name') == 'taskQuarter') {
            $('.date-picker-with-range').val('').datepicker('update');
            $('.date-picker-with-range').datepicker('setStartDate', $(this).children('option:selected').attr('data-dateFrom'));
            $('.date-picker-with-range').datepicker('setEndDate', $(this).children('option:selected').attr('data-dateTo'));
        } else {
            if ($(this).children('option:selected').attr('value') == 1) {
                var date = new Date(),
                    finalDate;

                finalDate = ((date.getDate() <= 9) ? 0 : '') +
                    date.getDate() +
                    '-' + ((date.getMonth() + 1 <= 9) ? 0 : '') +
                    (date.getMonth() + 1) +
                    '-' + date.getFullYear();

                setTimeout(() => {
                    $('[name="taskQuarter"]').val([1]).trigger('change');
                    $('[name="date"]').val(finalDate).datepicker('update');
                }, 1000);
                $('[name="taskQuarter"], [name="date"]').closest('.form-group').hide();
            } else {
                $('[name="taskQuarter"], [name="date"]').val('').trigger('change').closest('.form-group').show();
            }
        }
    });


    /*------Youtube Play-------*/
    onYouTubeIframeAPIReady()

    function onYouTubeIframeAPIReady() {
        var youtubePlayer, getYoutubeVideoId = document.getElementById('youtubePlayer').getAttribute('data-id');
        youtubePlayer = new YT.Player('youtubePlayer', {
            height: '400px',
            width: '100%',
            videoId: getYoutubeVideoId,
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    function onPlayerReady(event) {
        event.target.playVideo();
    }

    var done = false;

    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING && !done) {
            setTimeout(stopVideo, 6000);
            done = true;
        }
    }

    function stopVideo() {
        youtubePlayer.stopVideo();
    }



});
