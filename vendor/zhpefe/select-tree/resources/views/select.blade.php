<style>
    #{{$vars['id']}} select{
        margin-right: 5px;
    }
</style>
<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label for="{{$vars['id']}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
        <div class="form-inline" id="{{$vars['id']}}">

        </div>
        <input type="hidden" name="{{$name}}" id="{{$column}}" value="{{ old($column, $value) }}">
        @include('admin::form.help-block')
    </div>
</div>

<script>
    (function(){
        var addSelect = function(parent_id){
            $.get("{{$vars['url']}}", {q: parent_id}, function(data){
                if(data.hasOwnProperty('children') && data.children.length){
                    var select = $("<select></select>");
                    select.addClass('form-control');
                    select.append('<option selected value="0">please select..</option>');

                    $.each(data.children, function(i,v){
                        select.append(`<option value="${v.id}">${v.title}</option>`);
                    });
                    $("#{{$vars['id']}}").append(select);
                    select.change(function(){
                        var that = $(this);
                        that.nextAll().remove();
                        $("#{{$column}}").val(that.val());
                        if( that.val() ){
                            addSelect(that.val());
                        }
                    });
                }
            });
        };
        var initSelect = function(id){
            $.get("{{$vars['url']}}", {q: id}, function(data){
                if(data.hasOwnProperty('siblings') && data.siblings.length){
                    var select = $("<select></select>");
                    select.addClass('form-control');
                    select.append('<option selected value="0">please select..</option>');

                    $.each(data.siblings, function(i,v){
                        select.append(`<option value="${v.id}" ${v.id - 0 == id - 0 ? 'selected': ''}>${v.title}</option>`);
                    });
                    $("#{{$vars['id']}}").prepend(select);
                    select.change(function(){
                        var that = $(this);
                        that.nextAll().remove();
                        $("#{{$column}}").val(that.val());
                        if( that.val() ){
                            addSelect(that.val());
                        }
                    });
                    if(data.myself.parent_id - 0 != "{{$vars['top_id']}}" - 0) {
                        initSelect(data.myself.parent_id);
                    }
                }
            });
        };
        if ("{{$vars['url']}}") {
            if($("#{{$column}}").val()){
                initSelect($("#{{$column}}").val());
            }else{
                addSelect({{$vars['top_id']}});
            }
        } else {
            $("#{{$vars['id']}}").append('select-tree: You need $form->select_tree(column,label)->ajax()');
        }
    }())
</script>

