<script id="prices-template" type="text/x-custom-template">
    <div class="row animated fadeIn single-price">

        <div class="col-12">
            <div class="row">
                @foreach ($attributeGroups as $attributeGroup)
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label>{{ $attributeGroup->name }}</label>
                            <select class="form-control price-attribute-select" name="attribute">
                                <option value="">انتخاب کنید</option>
                                @foreach ($attributeGroup->get_attributes as $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-3 col-12">
            <div class="form-group">
                <label>قیمت</label>
                <input type="number" data-unit="{{currencyTitle()}}" class="form-control amount-input price" name="price" required>
            </div>
        </div>

        <div class="col-md-3 col-12">
            <div class="form-group">
                <label>تخفیف</label>
                <input type="number" class="form-control discount" name="discount" min="0" max="100" placeholder="%">
            </div>
        </div>

        <div class="col-md-3 col-12">
            <div class="form-group">
                <label>بیشترین تعداد مجاز در هر سفارش</label>
                <input type="number" class="form-control" name="cart_max" min="1">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="form-group">
                <label>کمترین تعداد مجاز در هر سفارش</label>
                <input type="number" class="form-control" name="cart_min" min="1">
            </div>
        </div>

            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label>قیمت عمده</label>
                    <input type="number" class="form-control discount" name="discount_major[]"  min="0" max="100" placeholder="%">
                </div>
            </div>
            <div class="col-md-4 col-12">

                <div class="form-group">
                    <label>کمترین تعداد</label>
                    <input type="number" class="form-control" name="min[]"  min="0">
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label>بیشترین تعداد</label>
                    <input type="number" class="form-control" name="max[]"  min="0">
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label>قیمت عمده</label>
                    <input type="number" class="form-control discount" name="discount_major[]"  min="0" max="100" placeholder="%">
                </div>
            </div>
            <div class="col-md-4 col-12">

                <div class="form-group">
                    <label>کمترین تعداد</label>
                    <input type="number" class="form-control" name="min[]"  min="0">
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label>بیشترین تعداد</label>
                    <input type="number" class="form-control" name="max[]"  min="0">
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label>قیمت عمده</label>
                    <input type="number" class="form-control amount-input price" name="discount_major[]"   placeholder="قیمت عمده">
                </div>
            </div>
            <div class="col-md-4 col-12">

                <div class="form-group">
                    <label>کمترین تعداد</label>
                    <input type="number" class="form-control" name="min[]"  min="0">
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label>بیشترین تعداد</label>
                    <input type="number" class="form-control" name="max[]"  min="0">
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label>قیمت عمده</label>
                    <input type="number" class="form-control discount" name="discount_major[]"  min="0" max="100" placeholder="%">
                </div>
            </div>
            <div class="col-md-4 col-12">

                <div class="form-group">
                    <label>کمترین تعداد</label>
                    <input type="number" class="form-control" name="min[]"  min="0">
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label>بیشترین تعداد</label>
                    <input type="number" class="form-control" name="max[]"  min="0">
                </div>
            </div>


        <div class="col-md-3 col-12">
            <div class="form-group">
                <label>موجودی انبار</label>
                <input type="number" class="form-control" name="stock" min="0" required>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="form-group">
                <label>قیمت نهایی</label>
                <input type="text" class="form-control final-price" disabled>
            </div>
        </div>

        <div class="col-md-3 col-12">
            <div class="form-group">
                <label>تایپ قیمت</label>
                <select name="title" class="form-control">
                    <option value="">انتخاب کنید</option>
                    @for($i=1;$i<=10;$i++)
                        <option value="fldTipFee{{$i}}" >
                            @if("fldTipFee".$i=="fldTipFee1")
                                fldTipFee{{$i}} (قیمت اصلی)
                            @elseif("fldTipFee".$i=="fldTipFee2")
                                fldTipFee{{$i}} (تخفیف ها)

                            @else
                                fldTipFee{{$i}}
                            @endif
                        </option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <button type="button" class="btn btn-flat-danger waves-effect waves-light remove-product-price custom-padding">حذف</i></button>
        </div>

        <div class="col-md-12"><hr></div>
    </div>
</script>
