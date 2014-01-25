var LinkPage =
{
    init: function()
    {
        $('#check-all').on('change', function() {
            if ($(this).prop('checked'))
            {
                $('.link-checkbox').prop('checked', true);
            }
            else
            {
                $('.link-checkbox').prop('checked', false);
            }
        });
        
        $('#delete-checked').click(function(e) {
            e.preventDefault();
            
            var list = '';
            $('.link-checkbox').each(function() {
                if ($(this).prop('checked'))
                {
                    if (list.length > 0)
                    {
                        list += ',';
                    }
                    list += $(this).val();
                }
            });
            if (list.length === 0)
            {
                return ;
            }

            if (!confirm('削除します。よろしいですか？'))
            {
                return ;
            }

            $('#form_ids').val(list);
            $('#delete-checked-form').submit();
        });
    }
};
$(document).ready(LinkPage.init);
