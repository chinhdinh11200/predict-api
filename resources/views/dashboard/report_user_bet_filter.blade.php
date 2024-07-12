<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-end align-items-center" id="{{$id}}">
            <div class="dropdown chart-dropdown">
                <button class="btn btn-sm btn-light shadow-0 dropdown-toggle p-0 waves-effect" data-toggle="dropdown">
                    {{trans('admin.dashboard.report_user_bet')[$initDropdown]}}
                </button>
                <div class="dropdown-menu" style="left: inherit; right: 0px;">
                    @foreach (trans('admin.dashboard.report_user_bet') as $key => $value)
                    <li class="dropdown-item"><a href="javascript:void(0)" class="select-option"
                            data-option="{{$key}}">{{$value}}</a></li>
                    @endforeach
                </div>
            </div>
            <div class="d-flex ml-2">
                <div class="input-group">
                    <span class="input-group-prepend"><span class="input-group-text bg-white"><i
                                class="fa fa-calendar fa-fw"></i></span></span>
                    <input autocomplete="off" type="text" name="form1[date-start]" value="{{$dateFrom}}"
                        class="form-control time-start field_form1_date-start_" style="width: 150px" initialized="1">
                </div>
                <div class="input-group ml-2">
                    <span class="input-group-prepend"><span class="input-group-text bg-white"><i
                                class="fa fa-calendar fa-fw"></i></span></span>
                    <input autocomplete="off" type="text" name="form1[date-start]" value="{{$dateTo}}"
                        class="form-control time-end field_form1_date-start_" style="width: 150px" initialized="1">
                </div>
            </div>
            <div class="ml-2">
                <button class="btn btn-sm btn-info btn-submit">{{__('admin.search')}}</button>
            </div>
        </div>
    </div>
</div>

<script>
Dcat.ready(function() {
    var options = {
        "format": "DD-MM-YYYY",
        "locale": "vi"
    };
    var start = $('.time-start');
    var end = $('.time-end');

    start.datetimepicker(options);
    end.datetimepicker($.extend(options, {
        useCurrent: false,
    }));
    start.on("dp.change", function(e) {
        end.data("DateTimePicker").minDate(e.date)
    });
    end.on("dp.change", function(e) {
        start.data("DateTimePicker").maxDate(e.date);
    });
});;
</script>
