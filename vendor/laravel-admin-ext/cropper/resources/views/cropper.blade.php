<style>
    .cropper-img{
        max-width: 300px;
        max-height: 300px;
        border: 1px solid #ddd;
        box-shadow: 1px 1px 5px 0 #a2958a;
        padding: 6px;
        float: left;
        clear: both;
    }
    .cropper-btn{
        margin-bottom: 10px;
    }
    .cropper-file{
        display: none !important;
    }
    #cropping-img{
        max-width: 100%;
    }
    #cropping-div{
        width: 98%;
        height: 98%;
        margin: 0 auto;
    }
</style>
<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')
        <div class="input-group">
        <div data-id="{{$id}}" class="btn btn-info pull-left cropper-btn">浏览</div>
        <input id="{{$id}}-file" {!! $attributes !!} data-id="{{$id}}" class="cropper-file" type="file" accept="image/*"/>
        <img id="{{$id}}-img" {!! $attributes !!} data-id="{{$id}}" class="cropper-img" {!! empty($value) ? '' : 'src="'.old($column, $value).'"'  !!}>
        <input id="{{$id}}-input" type="hidden" name="{{$name}}" value="{{ old($column, $value) }}" {!! $attributes !!} />
        </div>
        @include('admin::form.help-block')

    </div>
</div>

