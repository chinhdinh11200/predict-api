<div>
    <div class="row justify-content-center font-weight-normal" style="font-size: 28px;">{{__('admin.pool.title.value')}}
    </div>
    <div class="d-flex justify-content-center align-items-center" style="height: 80px;">
        <p class="p-0 m-0 font-weight-bold" style="font-size: 48px; line-height: 48px;">
            $ {{App\Helpers\NumberHelper::admin_number_format_no_zero(floatval($pool->value))}}
        </p>
    </div>
    <div class="row justify-content-center mt-1">
        <div class="col-md-4">
            <div class="d-flex justify-content-between">
                <a href="{{route('dcat.admin.pool.edit', ['pool' => $pool->id, 'is_add' => true])}}"
                    class="btn btn-success" style="font-size: 14px;">{{__('admin.pool.btn_deposit')}}</a>
                <a href="{{route('dcat.admin.pool.edit', ['pool' => $pool->id, 'is_add' => false])}}"
                    class="btn btn-danger" style="font-size: 14px;">{{__('admin.pool.btn_withdraw')}}</a>
            </div>
        </div>
    </div>
</div>
