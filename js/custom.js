jQuery(document).ready(function($){

    $('i.fa').popover();
    var currency = php_variables.php_currency;

    function calculate(){

        var data=[];


        var dataBalance=[];
        var dataPaidInterest=[];
        var dataPrincipal=[];

        var value = $('#input_value').val();
        var downPayment = $('#input_down_payment').val();
        var l = value-downPayment;

        var interest = $('#input_interest').val();
        if ($("#interest_option").val()=="Yearly"){
            var r = (interest/100)/12;
        }else if($("#interest_option").val()=="Monthly"){
            var r = interest/100;
        }

        var duration = $('#input_duration').val();

        if ($("#term_option").val()=="Years"){
            var n = duration*12;
        }else if($("#term_option").val()=="Months"){
            var n = duration;
        }

        /*---- Basic Monthly Payment Calcutation  ----*/

        var P = l*r/ ( 1- Math.pow(1+r,-n) );

        /*---- Producing results in Card ----*/
        $('.card').show();
        $('#result').empty();
        $('#result').append('<p>The remaining dept is '+currency+' ' + l+'</p>');
        $('#result').append('<p>The monthly rate is ' + Math.round(r*100 * 100) / 100+'%</p>');
        $('#result').append('<p>There are  '+ n+' Payment Periods</p>');
        $('#result').append('<p>The Monthly Payment: '+currency+' ' + Math.round(P * 100) / 100+'</p>');
        $('#result').append('<p>Total Payment: '+currency+' ' + Math.round(P*n * 100) / 100+'</p>');
        $('#result').append('<p>Total Interest Payment: '+currency+' ' + Math.round((P*n-l) * 100) / 100+'</p>');

        /*--- Create payment schedule ---*/

        var principal=0;
        var paidInterest=0;
        var balance=Number(l);

        data.push([principal, paidInterest,balance]);
        dataPrincipal.push(principal);
        dataPaidInterest.push(paidInterest);
        dataBalance.push(balance);

        for(x=0;x<n;x++){

            console.log(x, n);
            if(x==n-1){
                console.log('x== n-1');
                paidInterest = Math.round((P*n-l) * 100) / 100 ;
                principal=Number(l);
                balance=0;
            }else{
                paidInterest+=Number( (r*balance).toFixed(2) );
                principal=Number( ((x+1)*P-paidInterest).toFixed(2) );
                balance=Number( (balance-(P-Number( (r*balance).toFixed(2) ) )).toFixed(2) );
            }

            data.push([principal, paidInterest,balance]);
            dataPrincipal.push(principal);
            dataPaidInterest.push(paidInterest);
            dataBalance.push(balance);

        }
        // console.log(data);
        //console.log(dataPaidInterest);
        console.log(dataBalance);
        //console.log(dataPrincipal);

        //Google Charts


        //google.load("visualization", "1", {packages:["corechart"]});
        //google.load("visualization", "1",{"callback" : drawChart});
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = new google.visualization.DataTable();
            data.addColumn('number', 'Payment Period');
            data.addColumn('number', 'Balance');
            data.addColumn('number', 'Principal');
            data.addColumn('number', 'Paid Interest');

            for(i = 0; i < dataBalance.length; i++){
                data.addRow( [(i+1), dataBalance[i], dataPrincipal[i], dataPaidInterest[i] ]);
            }
            var options = {
                title: 'Amortization repayment schedule',
                curveType: 'function',
                legend: { position: 'bottom' },
                colors: ['#29b6f6', '#33CC33', '#DD2121'],
                areaOpacity: 0.5
            };

            var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));

            chart.draw(data, options);
        }


    }

    $('#calculate').click(function(){
        console.log('click');
        console.log( currency );
        calculate();
    });
    
});

