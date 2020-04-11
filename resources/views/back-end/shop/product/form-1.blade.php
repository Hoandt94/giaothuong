<div class="panel-body">
    <fieldset class="form-horizontal">
    	<input type="hidden" name="_id" value="{{isset($obj) ? $obj->id : ''}}">
        @if(isset($obj))
        <div class="form-group {{ $errors->has('product_code') ? 'has-error' : '' }}">
            <label class="col-sm-2 control-label">Mã sản phẩm (*) </label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="product_code" id="product_code" 
                value="{{isset($obj) ? $obj->product_code : old('product_code')}}" readonly>
            </div>
        </div>
        @endif
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label class="col-sm-2 control-label">Tên sản phẩm (*) </label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="name" id="name" 
                value="{{isset($obj) ? $obj->name : old('name')}}" required>
            </div>
        </div>
        <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
            <label class="col-sm-2 control-label">Đường dẫn (*) </label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="slug" id="slug" 
                value="{{isset($obj) ? $obj->slug : old('slug')}}" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Loại sản phẩm (*)</label>
            <div class="col-md-4">
                <select class="form-control m-b" name="type" id="type" required>
                    <option label=""></option>
                    @foreach(config('admin.types') as $key=>$value)
                         <option value="{{$key}}" {{isset($obj) ? ($obj->type == $key ? 'selected' : '') : 
                                    (old('type') == $key ? 'selected' : '')}}>
                        {{$value}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Danh mục sản phẩm (*)</label>
            <div class="col-md-4">
                <select class="form-control m-b" name="type_product_id" id="type_product_id" required>
                    <option label=""></option>
                    @foreach($type_products as $type)
                         <option value="{{$type->id}}" {{isset($obj) ? ($obj->type_product_id == $type->id ? 'selected' : '') : 
                                    (old('type_product_id') == $type->id ? 'selected' : '')}}>
                        {{$type->name}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Danh mục sản phẩm (thêm)</label>
            <div class="col-md-4">
                <select class="form-control m-b" name="type_product_id_2" id="type_product_id_2">
                    <option label=""></option>
                    @foreach($type_products_2 as $key=>$value)
                         <option value="{{$key}}" {{isset($obj) ? ($obj->type_product_id_2 == $key ? 'selected' : '') : 
                                    (old('type_product_id_2') == $key ? 'selected' : '')}}>
                        {{$value}}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <input type='hidden' name='shop_id' value={{auth()->guard('shop')->user()->id}}>
        @include('back-end.partials.status')
    </fieldset>
</div>