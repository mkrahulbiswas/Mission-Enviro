$('document').ready(function () {


    $('#countryDDD, .countryDDD').change(function () {

        var html = '<option value="">Select State</option>';
        $("#stateDDD, .stateDDD").text('');
        if ($(this).val() == '') {
            $("#stateDDD, .stateDDD").append(html);
        } else {
            $.ajax({
                url: $(this).attr('data-action') + '/' + $(this).val(),
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#stateDDD, .stateDDD").append(html);
                    } else {
                        $.each(msg.data, function (key, value) {
                            html += '<option value="' + value['id'] + '">' + value['stateName'] + '</option>'
                        });
                        $("#stateDDD, .stateDDD").append(html);
                    }
                }
            });
        }
    });


    $('#stateDDD, .stateDDD').change(function () {

        var html = '<option value="">Select City</option>';
        $("#cityDDD, .cityDDD").text('');
        if ($(this).val() == '') {
            $("#cityDDD, .cityDDD").append(html);
        } else {
            $.ajax({
                url: $(this).attr('data-action') + '/' + $(this).val(),
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $("#cityDDD, .cityDDD").append(html);
                    } else {
                        $.each(msg.data, function (key, value) {
                            html += '<option value="' + value['id'] + '">' + value['cityName'] + '</option>'
                        });
                        $("#cityDDD, .cityDDD").append(html);
                    }
                }
            });
        }
    });


    $('.levelDDD').change(function () {

        var html = '<option value="">Select Category Type</option>';
        $(".classDDD").text('');
        if ($(this).val() == '') {
            $(".classDDD").append(html);
        } else {
            $.ajax({
                url: $(this).attr('data-action') + '/' + $(this).val(),
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $(".classDDD").append(html);
                    } else {
                        $.each(msg.data, function (key, value) {
                            html += '<option value="' + value['id'] + '">' + value['class'] + '</option>'
                        });
                        $(".classDDD").append(html);
                    }
                }
            });
        }
    });


    $('.taskLevelDDD').change(function () {

        var html = '<option value="">Select Category Type</option>';
        $(".taskQuarterDDD").text('');
        if ($(this).val() == '') {
            $(".taskQuarterDDD").append(html);
        } else {
            $.ajax({
                url: $(this).attr('data-action') + '/' + $(this).val(),
                type: 'get',
                dataType: 'json',
                beforeSend: function () {
                    $("#loader").css("display", 'block');
                },
                success: function (msg) {
                    $("#loader").css("display", 'none');
                    if (msg.status == 0) {
                        $(".taskQuarterDDD").append(html);
                    } else {
                        $.each(msg.data, function (key, value) {
                            html += '<option value="' + value['id'] + '" data-dateFrom="' + value['dateFrom'] + '"  data-dateTo="' + value['dateTo'] + '">' + value['title'] + '  (' + value['dateFrom'] + ' -- ' + value['dateTo'] + ')' + '</option>'
                        });
                        $(".taskQuarterDDD").append(html);
                    }
                }
            });
        }
    });


});
