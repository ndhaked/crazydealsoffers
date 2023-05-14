{!! Html::script('lobibox/lib/jquery.1.11.min.js') !!} 
{!! Html::script('lobibox/js/lobibox.js') !!}
@if(isset($errors))
    @if ($errors->any())
        <script>
         $(function () {
                (function () {
                    Lobibox.notify('error', {
                        rounded: false,
                        delay: 5000,
                        delayIndicator: true,
                        position: "top right",
                        msg: "<?php foreach($errors->all() as $error){ echo $error."<br>"; } ?>"
                    });
                })();
            });
        </script>
    @endif
@endif
    @if(session()->has('success') or session()->has('warning') or session()->has('info') or session()->has('danger') or session()->has('error'))
        @foreach (['danger', 'warning', 'success', 'info','error'] as $msg)
            @if(session()->has($msg))
                <script>
                var msgType = "{{$msg}}"
                 $(function () {
                        (function () {
                            Lobibox.notify(msgType, {
                                rounded: false,
                                delay: 10000,
                                delayIndicator: true,
                                 position: "top right",
                                msg: "{{ session()->get($msg)}}"
                            });
                        })();
                    });
                </script>
                <?php  session()->forget($msg); ?>
            @endif
        @endforeach
    @endif
@if (session('status'))
<script>
    var msgType = "success"
     $(function () {
            (function () {
                Lobibox.notify(msgType, {
                    rounded: false,
                    delay: 4000,
                    delayIndicator: true,
                     position: "top right",
                    msg: "{{ session('status') }}"
                });
            })();
        });
</script>
@endif
<style type="text/css">
.lobibox-notify-msg{max-height:100px !important;}
</style>
<script type="text/javascript">
function ConfirmDeleteLovi(id,title,href)
{
    Lobibox.confirm({
    title: title+' Confirmation',
    msg: 'Are you sure you, want to '+title+'?',
    callback: function ($this, type, ev) {
        if (type === 'yes'){
                if(title == 'Restore' || title == 'Active' || title == 'Inactive'){
                    window.location.href = href;
                }else{
                    if(href == 'logout'){
                        $("#"+id).submit();  
                        return true;
                    }else{
                        $("."+id).submit();  
                    } 
                }
            }else{
                return false;
            }
        }
    });
}

function AjaxActionTableDrow(obj)
{
    var _action  =  obj.getAttribute('data-action'),
        title    =  obj.getAttribute('data-title'),
        refresh    =  obj.getAttribute('data-refresh'),
        reload    =  obj.getAttribute('data-reload');

    var _type = 'GET';
    if(title=='Delete'){ _type = 'DELETE'; }
    var table = $('#data_filter').DataTable();
    Lobibox.confirm({
    title: title+' Confirmation',
    msg: 'Are you sure you, want to '+title+'?',
    callback: function ($this, type, ev) {
        if (type === 'yes'){
                $.ajax({
                    type: _type,
                    url: _action,
                    success: function (data) {
                        if(refresh == 'yes'){
                            $('.search_trigger').trigger('click');
                        }
                        table.draw();
                        Lobibox.notify(data.type, {
                            rounded: false,
                            delay: 4000,
                            delayIndicator: true,
                             position: "top right",
                            msg: data.message
                        });
                        
                        if(reload == 'yes') {
                            location.reload();
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }else{
                return false;
            }
        }
    });
}
</script>