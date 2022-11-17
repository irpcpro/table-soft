let tableSoftScript = {
    init: function (){
        tableSoftScript.addField();
    },

    addField: function(){
        $("#add-row").on('click', function(){
            let countRow = $("#item-holder .table-setting-items").length + 1;

            $.ajax({
                method: "POST",
                url: ROUTE_AJAX,
                data: {
                    countRow,
                    _token: CSRF_TOKEN
                }
            }).done(function(data) {
                $("#item-holder").append(data);
            });
        });
    }
};

tableSoftScript.init();
