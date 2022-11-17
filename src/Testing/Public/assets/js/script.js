let tableSoftScript = {
    init: function (){
        tableSoftScript.addField();
        tableSoftScript.removeField();
    },

    addField: function(){
        $("#add-row").on('click', function(){
            $.ajax({
                method: "POST",
                url: ROUTE_AJAX,
                data: {
                    _token: CSRF_TOKEN
                }
            }).done(function(data) {
                $("#item-holder").append(data);
            });
        });
    },

    removeField: function(){
        $("#item-holder").on('click', '.removeRowItem', function(){
            let getThis = $(this);
            getThis.parents('.table-setting-items').fadeOut(250, function(){
                $(this).remove()
            });
        });
    }
};

tableSoftScript.init();
