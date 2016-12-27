$(document).ready(function () {
    $('.collapsible').collapsible();

    $.getJSON("API/view.php", function(data){
        var row;
        for(i in data.order){
            row = '<tr><td>' + data.order[i].trainNum 
                + '</td><td>' + data.order[i].date
                + '</td><td>' + data.order[i].departure
                + '</td><td>' + data.order[i].arrival 
                + '</td><td>' + data.order[i].departTime
                + '</td><td>' + data.order[i].arriveTime 
                + '</td><td>' + '<a class="btn-floating btn waves-effect waves-light red"><i class="material-icons" id="chooseRefund">delete</i></a>'
                + '</td></tr>';

            $("#table-content").append(row);
        }
    });

    $("#chooseRefund").click(refund);
});

function refund(){
    
}