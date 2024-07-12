<hr class="m-0">
<div class="">
    <div class="row">
        <div class="col-md-2 text-right control-label"> {{__('admin.users.bet_summary.buy')}} : </div>
        <div class="col-md-8 pl-0 control-label font-weight-bold">
            {{ App\Helpers\NumberHelper::admin_number_format_no_zero(floatval($data['buy']))}} $
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 text-right control-label"> {{__('admin.users.bet_summary.sell')}} : </div>
        <div class="col-md-8 pl-0 control-label font-weight-bold">
            {{App\Helpers\NumberHelper::admin_number_format_no_zero(floatval($data['sell']))}} $
        </div>
    </div>
    <div class="row ">
        <div class="col-md-2 text-right control-label"> {{__('admin.users.bet_summary.pool_buy')}} :
        </div>
        <div class="col-md-8 pl-0 control-label font-weight-bold">
            {{App\Helpers\NumberHelper::admin_number_format_no_zero(floatval($data['poolBuy']))}} $
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 text-right control-label"> {{__('admin.users.bet_summary.pool_sell')}} :
        </div>
        <div class="col-md-8 pl-0 control-label font-weight-bold">
            {{App\Helpers\NumberHelper::admin_number_format_no_zero(floatval($data['poolSell']))}} $
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 text-right control-label"> {{__('admin.users.bet_summary.regulation')}} :
        </div>
        <div class="col-md-8 pl-0 control-label font-weight-bold">
            <form action="{{route('dcat.admin.user-bet.regulation')}}" method="post" id="store-regulation">
                @csrf
                <div class="d-flex">
                    <div class="vs-radio-con vs-radio-primary my-0" style="margin-right: 32px">
                        <input value="{{\App\Models\Bet::UP}}" name="type_target" class="regulation" type="radio"
                            {{$historyCommandBet && $historyCommandBet->type_target == \App\Models\HistoryCommandBet::BUY_TYPE ? 'checked' : ''}}
                            {{ $historyCommandBet ? "disabled" : ''}}>
                        <span class="vs-radio vs-radio-">
                            <span class="vs-radio--border"></span>
                            <span class="vs-radio--circle"></span>
                        </span>
                        <span>{{__('admin.users.bet_type.' . \App\Models\Bet::UP)}}</span>
                    </div>
                    <div class="vs-radio-con vs-radio-primary my-0" style="margin-right: 32px">
                        <input value="{{\App\Models\Bet::DOWN}}" name="type_target" class="regulation" type="radio"
                            {{$historyCommandBet && $historyCommandBet->type_target == \App\Models\HistoryCommandBet::SELL_TYPE ? 'checked' : ''}}
                            {{ $historyCommandBet ? "disabled" : ''}}>
                        <span class="vs-radio vs-radio-">
                            <span class="vs-radio--border"></span>
                            <span class="vs-radio--circle"></span>
                        </span>
                        <span>{{__('admin.users.bet_type.' . \App\Models\Bet::DOWN)}}</span>
                    </div>
                    <button class="btn btn-info btn-sm" id="btn-regulation" type="submit"
                        {{ $historyCommandBet ? "disabled" : ''}}>{{__('admin.save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function submitRegulation(e, socket) {
    var form = $(e.target);
    var typeTarget = $("input[name='type_target']:checked").val();
    $('#btn-regulation').attr('disabled', true);
    $.ajax({
        url: "{{route('dcat.admin.user-bet.regulation')}}",
        type: "POST",
        data: {
            session_id: "{{$sessionId ?? null}}",
            type_target: typeTarget,
        },
        success: function(data) {
            if (data) {
                if (data.status_code == '422') {
                    toastr.error("{{__('admin.users.bet_summary.regulation_error')}}");
                    $('#btn-regulation').attr('disabled', false);
                } else {
                    $('.regulation').attr('disabled', true);
                    $('#btn-regulation').attr('disabled', true);
                    toastr.success(
                        "{{__('admin.users.bet_summary.regulation_success')}}");
                    socket.emit('BIFIX_ADMIN_REGULATION', {
                        sessionId: "{{$sessionId ?? null}}",
                        typeTarget: typeTarget,
                    });
                }
            } else {
                toastr.error("{{__('admin.users.bet_summary.regulation_error')}}");
                $('#btn-regulation').attr('disabled', false);
            }
        },
        error: function(error) {
            console.log(error);
            toastr.error("{{__('admin.users.bet_summary.regulation_error')}}");
            $('#btn-regulation').attr('disabled', false);
        }
    })
}
</script>
