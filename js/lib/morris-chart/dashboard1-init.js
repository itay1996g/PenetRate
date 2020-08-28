// Dashboard 1 Morris-chart
$( function () {
	"use strict";


	// Extra chart
	Morris.Area( {
		element: 'extra-area-chart',
		data: [  {
				period: '2018 Q1',
				android: 10
        },{
				period: '2018 Q2',
				android: 20
        }, {
				period: '2019',
				android: 50
        }


        ],
		lineColors: [ '#26DAD2', '#fc6180', '#62d1f3', '#ffb64d', '#4680ff' ],
		xkey: 'period',
		ykeys: ['android' ],
		labels: ['android' ],
		pointSize: 0,
		lineWidth: 0,
		resize: true,
		fillOpacity: 0.8,
		behaveLikeLine: true,
		gridLineColor: '#e0e0e0',
		hideHover: 'auto'

	} );



} );
