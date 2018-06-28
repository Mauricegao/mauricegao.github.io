Plotly.d3.csv('js/month2.csv', function(err, rows){
function unpack(rows, key) {
  return rows.map(function(row) { return row[key]; });
}
  
var z_data=[ ]
for(i=0;i<25;i++)
{
  z_data.push(unpack(rows,i));
}

var data = [{
           z: z_data,
           type: 'surface'
        }];
  
var layout = {
    title: 'Edmonton Intl., Fog (vis <= 1/2 SM)',
    autosize: false,
    width: 1060  ,
    height: 1060,
	scene: {
		xaxis:{title: 'Month'},
		yaxis:{title: 'Hours (GMT)'},
		zaxis:{title: '% Occurrence'},
		},
    margin: {
        l: 65,
        r: 50,
        b: 65,
        t: 90,
  }
};
Plotly.newPlot('myDiv', data, layout);
});