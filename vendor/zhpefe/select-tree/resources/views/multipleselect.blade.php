<style>
    .{{$id}}_dropdown{
        margin-top:6px;
    }
    .{{$id}}_dropdown .dropdown-toggle{
        height:20px;
        padding: 6px 12px;
        border:1px solid #d2d6de;
        color:#555;
        font-size:14px;
    }
    .{{$id}}_dropdown .dropdown-menu >li{
        padding:8px;
        border-bottom: 1px solid rgba(0,0,0,.15);
    }
    .{{$id}}_dropdown .dropdown-menu >li:last-child{
        border-bottom: 0;
    }
    .{{$id}}_dropdown .dropdown-menu >li li{
        padding:5px;
        border-bottom: 1px dotted rgba(0,0,0,.15);
    }
    .{{$id}}_dropdown .dropdown-menu >li li:last-child{
        border-bottom: 0;
    }
    .{{$id}}_dropdown .dropdown-menu label{
        font-weight: 400;
    }
    .{{$id}}_dropdown .dropdown-menu ul{
        list-style: none;
    }
</style>
<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')
        <div class="dropdown {{$id}}_dropdown">
            <a class="dropdown-toggle">
                <span class="text"></span>&nbsp;&nbsp;
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu"></ul>
        </div>
        <select id="{{$id}}" name="{{$name}}[]" multiple style="display: none;"></select>
        @include('admin::form.help-block')
    </div>
</div>
<script>
    (function(){
        var checkbox_value = {{json_encode((array)old($column, $value))}};
        var select_value = function(id, title){
            var select = $('#{{$id}}');
            if(select.children(`option[value=${id}]`).length){
                select.children(`option[value=${id}]`).remove();
                $('.{{$id}}_dropdown .dropdown-toggle .text').text(function(i, o){
                    o = '|' + o + '|';
                    o = o.replace( `|${id}:${title}|`, '|' ).replace( `|${id}:${id}|`, '|' );
                    return o.replace( /^\||\|$/g, '' );
                })
            } else {
                $('#{{$id}}').append(`<option selected value="${id}">${title}</option>`);
                $('.{{$id}}_dropdown .dropdown-toggle .text').text(function(i, o){
                    return `${o?o + '|': ''}${id}:${title}`;
                })
            }
        };
        var addSelect = function(parent_id, dom){
            var init = arguments[2] ? 1: 0;
            $.get("{{$vars['url']}}", {q: parent_id}, function(data){
                if(data.hasOwnProperty('children') && data.children.length){
                    var checkbox = init ? '<ul>': '';
                    $.each(data.children, function(i,v){
                        checkbox +=
                            `<li><input type="checkbox" name="{{$name}}" class="{{$name}}_checkbox"
                            ${ Object.values(checkbox_value).includes(v.id) ? 'checked' : '' }
                            value="${v.id}" ></input><label>&emsp;<span class="title">${v.title}</span>
                            &emsp;<span class="caret"></span>
                            </label></li>`;
                    });
                    checkbox += init ? '</ul>' : '';
                    dom.append(checkbox);
                }
            });
        };
        Object.values(checkbox_value).forEach(function(item){
            select_value(item, item);
        });
        addSelect({{$vars['top_id']}}, $('ul.dropdown-menu'));
        $('.{{$id}}_dropdown').on('click', '.dropdown-menu li label', function(e){
            var li = $(this).parent()
            if(li.children('ul').length){
                li.children('ul').toggle();
            } else {
                addSelect($(this).prev().attr('value'), li, 1);
            }
        });
        $('.{{$id}}_dropdown').on('click', '.dropdown-menu li input[type=checkbox]', function(e){
            var that = $(this);
            select_value(that.val(), that.next().find('.title').text());
        });
        $('.{{$id}}_dropdown a.dropdown-toggle').on('click', function () {
            $(this).parent().toggleClass('open');
        });
    }())
</script>