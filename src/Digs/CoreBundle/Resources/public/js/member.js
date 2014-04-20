var MemberPage =
{
    init: function()
    {
        $('.change-activity-false').click(function(e) {
            e.preventDefault();
            if (!confirm('無効にします。よろしいですか？'))
            {
                return ;
            }
            $('#form_mid').val($(this).attr('mid'));
            $('#form_status').val('false');
            $('#delete-checked-form').submit();
        });
        $('.change-activity-true').click(function(e) {
            e.preventDefault();
            if (!confirm('有効にします。よろしいですか？'))
            {
                return ;
            }
            $('#form_mid').val($(this).attr('mid'));
            $('#form_status').val('true');
            $('#delete-checked-form').submit();
        });
    }
};
$(document).ready(MemberPage.init);
