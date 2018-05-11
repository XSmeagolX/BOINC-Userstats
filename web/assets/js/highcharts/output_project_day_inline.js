<script type="text/javascript">
	$(function () {
		var chart=new Highcharts.StockChart({
			chart: {
				renderTo: 'output_project_day_inline',
				defaultSeriesType: 'column',
				backgroundColor: '<?php echo $highchartsBGColor; ?>'
			},    
			navigator: {
				enabled: false,
				height: 40,
				series: {
					type: 'column',
					dataGrouping: {
						forced: true,
						units: [
							['day', [1]]
						]
					}, 
					lineWidth: 1,
					marker: {
						enabled: false
					},
					shadow: false
				}
			},
			rangeSelector: {
				inputEnabled: false,
				selected: 0
			}, 
			plotOptions: {
				column: {
					dataGrouping: {
						dateTimeLabelFormats: {
							day: ['<?php echo $tr_ch_dateformat; ?>']
						}
					}
				}
			},					
			xAxis: {
				type: 'datetime',
				ordinal: false,
				maxZoom: 14 * 1 * 24 * 3600000
			},
			yAxis: [{ // left y axis
				opposite: false,
				alternateGridColor: '<?php echo $highchartAlternateGridColor; ?>',
				labels: {
					enabled: false
				}
				}, { // right y axis
				linkedTo: 0,
				gridLineWidth: 0,
				opposite: true,
				labels: {
					enabled: false
				}
			}],
			series: [{
				name: '<?php echo $tr_ch_yaxis_day;?>',
				color: '<?php echo $highchartBarChartColor; ?>',
				data: [<?php echo $output_project_html;?>],
				dataGrouping: {
					forced: true,
					units: [
						['day', [1]]
					]
				} 
			}]
		});
	});
</script>
