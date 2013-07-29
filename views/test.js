var variations = 0;
var n = [];
var x = [];
var p = [];
var variance = [];
var variance95 = [];
var upper = [];
var lower = [];
var upper95 = [];
var lower95 = [];
var change = [];
var chartArray = [];
var winningChance = [];

google.load('visualization', '1', {packages: ['corechart']});

function drawVisualization() {
	jQuery('div#visualization').hide();
	jQuery('div#visualization').show();
	var dataTable = google.visualization.arrayToDataTable(chartArray, true);

	var chart = new google.visualization.CandlestickChart(document.getElementById('visualization'));
	chart.draw(dataTable, {title:'Conversion rates by treatment', legend:'none', vAxis:{title: 'Conversion rate %', format:'#,###%'}, enableInteractivity: true});
	jQuery('div#visualization').show();
}
	
function showWinners() {
	jQuery('#tblFooter').html('');
	jQuery('.winner').removeClass('winner');
	jQuery('.loser').removeClass('loser');
	var anyWinners = false;

	for (i=1;i<variations+1;i++) {
		
		// Winners
		if (lower[i]>upper[0] && winningChance[i]>0.95) {
			jQuery('tr.Treatment'+i).addClass('winner');
			jQuery('#tblFooter').html('<span style="display:none;float:right;"><strong>You have a statistically significant result!</strong></span>');
			anyWinners = true;
		}
		
		// Losers
		if (lower[0]>upper[i] && winningChance[i]<0.05) {
			jQuery('tr.Treatment'+i).addClass('loser');
			jQuery('#tblFooter').html('<span style="display:none;float:right;"><strong>You have a statistically significant result!</strong></span>');
			anyWinners = true;
		}

	}

	if (!(anyWinners)) {jQuery('#tblFooter').html('<span style="display:none;float:right;">Keep testing to detect a significant difference or move on...</span>');}

	jQuery('#tblFooter>span').show(1000);

}
		
function normalcdf(mean, sigma, to) {
		var z = (to-mean)/Math.sqrt(2*sigma*sigma);
		var t = 1/(1+0.3275911*Math.abs(z));
		var a1 =  0.254829592;
		var a2 = -0.284496736;
		var a3 =  1.421413741;
		var a4 = -1.453152027;
		var a5 =  1.061405429;
		var erf = 1-(((((a5*t + a4)*t) + a3)*t + a2)*t + a1)*t*Math.exp(-z*z);
		var sign = 1;
		if(z < 0)
		{
			sign = -1;
		}
		return (1/2)*(1+sign*erf);
	}

function fillTable() {
	jQuery('tbody>tr').hide();
	
	for (i=0;i<variations+1;i++) {
		jQuery('tr.Treatment'+i+'>td.Name').html(chartArray[i][0]);
		jQuery('tr.Treatment'+i+'>td.Sample').html(n[i].toString());
		jQuery('tr.Treatment'+i+'>td.Goal').html(x[i].toString());
		jQuery('tr.Treatment'+i+'>td.ConvR').html((Math.round(p[i]*10000)/100)+'%');
		jQuery('tr.Treatment'+i+'>td.Change').html((Math.round(change[i]*10000)/100)+'%');
		jQuery('tr.Treatment'+i+'>td.Chance').html((Math.round(winningChance[i]*10000)/100)+'%');
		jQuery('tr.Treatment'+i).show();
	}
	
	jQuery('tr.Treatment0>td.Chance').html('');
	jQuery('tr.Treatment0>td.Change').html('');
	jQuery('table#newspaper-b').show();
	}

function calculations() {

	for (i=0;i<variations+1;i++) {
		
		p[i] = x[i]/n[i];
		
		variance[i] = 1.282*(Math.sqrt(p[i]*(1-p[i])/n[i]));
		variance95[i] = 1.96*(Math.sqrt(p[i]*(1-p[i])/n[i]));
		
		upper[i] = p[i] + variance[i];
		lower[i] = p[i] - variance[i];

		upper95[i] = p[i] + variance95[i];
		lower95[i] = p[i] - variance95[i];
		
		winningChance[i] = normalcdf(p[0],variance95[0],p[i]);
		
		change[i] = (p[i]-p[0])/p[0];
		
		chartArray[i] = ['Treatment '+i,lower95[i],lower[i],upper[i],upper95[i]];
		
	}
	
	chartArray[0][0] = "Control";
	

}
	
function getData() {
	for (i=0;i<variations+1;i++) {
		n[i] = jQuery('input#sample-'+i).val();
		x[i] = jQuery('input#successes-'+i).val();
	}
}

function calculate() {
	variations=parseInt(jQuery('input#vars-num').val());
	getData();
	calculations();
	fillTable();
	drawChart();
	showWinners();
}	

function addRow() {

	variations=parseInt(jQuery('input#vars-num').val());
	
	if (variations<6) {
		variations = variations+1;
		jQuery('input#vars-num').attr('value', variations);
		showFields();
	}

}

function removeRow() {

	variations=parseInt(jQuery('input#vars-num').val());
	
	if (variations>1) {
		variations = variations-1;
		jQuery('input#vars-num').attr('value', variations);
		showFields();
	}

}

function showFields() {
			
	jQuery('div#Optional-data').hide(); 
	jQuery('div#Optional-data>span').hide();
	
	for (i=0;i<variations+1;i++) {
		jQuery('span#data'+i).show();
	}
	jQuery('div#Optional-data').show();
}



function drawChart() {

var urlBase = "http://chart.googleapis.com/chart?chs=600x425&cht=lc&chd=t0:";
var urlData = '';

urlData=urlData+'-1,';

for (i=0;i<variations+1;i++) {
	urlData = urlData + lower95[i] + ','
}

urlData=urlData+'-1|-1,';

for (i=0;i<variations+1;i++) {
	urlData = urlData + lower[i] + ','
}

urlData=urlData+'-1|-1,';

for (i=0;i<variations+1;i++) {
	urlData = urlData + upper[i] + ','
}

urlData=urlData+'-1|-1,';

for (i=0;i<variations+1;i++) {
	urlData = urlData + upper95[i] + ','
}

urlData=urlData+'-1|-1,';

for (i=0;i<variations+1;i++) {
	urlData = urlData + p[i] + ','
}

var urlTreatments = '';

for (i=1;i<variations+1;i++) {
	urlTreatments = urlTreatments + 'Treatment+' + i + '|';
}

urlData=urlData+'-1&chm=F,00AABB,0,,20|H,FF0000,0,,1:20|H,FF0000,3,,1:20|H,000000,4,,1:20&chxt=y&chds=a&chtt=Conversion+Rates+by+Treatment&chxt=x,y,y&chxl=0:||Control|'+urlTreatments+'|1:|2:|Conv.%20Rate&chxs=1N*p&chxp=2,'+p[0];

jQuery('div#visualization').html('<img src=\"'+urlBase+urlData+'\"/>');
}