var MemberGroupPage =
{
    init: function()
    {
        $('#submit-delete').click(function(e) {
            e.preventDefault();
            if (!confirm('削除します。よろしいですか？'))
            {
                return ;
            }
            $('#delete-form').submit();
        });
    }
};
$(document).ready(MemberGroupPage.init);
