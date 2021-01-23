<style>
    #{{$vars['id']}} select{
        margin-right: 5px;
    }
</style>
<div class="form-inline" id="{{$vars['id']}}"></div>
<input type="hidden" name="{{$name}}" id="{{$name}}" value="">
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
                        $("#{{$name}}").val(that.val());
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
                        $("#{{$name}}").val(that.val());
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
        var query_s = function() {
            var r = window.location.search.substr(1).match(/(^|&){{$name}}=([^&]*)(&|$)/i);
            return r == null || r[2] == null || r[2] == "" || r[2] == "undefined" || r[2] == "0" ? "" : r[2];
        }
        if ("{{$vars['url']}}") {
            var v = query_s()
            $("#{{$name}}").val(v);
            if(v){
                initSelect(v);
            }else{
                addSelect({{$vars['top_id']}});
            }
        } else {
            $("#{{$vars['id']}}").append('select-tree: You need $filter->select_tree(column,label)->ajax()');
        }

    }())
</script>

