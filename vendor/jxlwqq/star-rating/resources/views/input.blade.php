<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="input-group">

            <input {!! $attributes !!} class="rating" id="{!! $id !!}" name="{{$name}}" value="{{ old($column, $value) }}"/>

        </div>

        @include('admin::form.help-block')
    </div>
</div>
