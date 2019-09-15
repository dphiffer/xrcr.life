var colors;
var limit = 0;
var grid = [];

function setup() {
	if ($('#canvas').length == 0) {
		return;
	}
	var w = $('#canvas').width();
	var h = $('#canvas').height();
	var canvas = createCanvas(w, h);
	canvas.parent('#canvas');
	strokeWeight(6);
	colors = {
		green: color(20, 170, 55),
		black: color(0, 0, 0),
		lemon: color(247, 238, 106),
		light_blue: color(117, 208, 241),
		pink: color(237, 155, 196),
		purple: color(152, 98, 151),
		light_green: color(190, 210, 118),
		warm_yellow: color(255, 193, 30),
		bright_pink: color(207, 98, 151),
		red: color(220, 79, 0),
		dark_blue: color(56, 96, 170),
		angry: color(200, 0, 130)
	};
	background(colors.lemon);
	fill(colors.lemon);
	frameRate(30);

	var logo_colors = [
		'red',
		'pink',
		'warm_yellow',
		'light_green',
		'green',
		'light_blue',
		'dark_blue',
		'angry',
		'bright_pink'
	];
	var count = 0;
	for (var x = 45; x < $('#canvas').width(); x += 85) {
		for (var y = 45; y < $('#canvas').height(); y += 85) {
			grid.push({
				x: x,
				y: y,
				color: logo_colors[count % logo_colors.length]
			});
			count++;
		}
	}
	grid = shuffle(grid);
}

function shuffle(a) {
	var j, x, i;
	for (i = a.length - 1; i > 0; i--) {
		j = Math.floor(Math.random() * (i + 1));
		x = a[i];
		a[i] = a[j];
		a[j] = x;
	}
	return a;
}

function xr_logo(x, y, color) {
	stroke(color);
	var r = 75;
	var x1 = x - r * 0.30;
	var x2 = x + r * 0.30;
	var y1 = y - r * 0.30;
	var y2 = y + r * 0.30;
	circle(x, y, r);
	line(x1, y1, x2, y1);
	line(x1, y2, x2, y2);
	line(x1, y1, x2, y2);
	line(x1, y2, x2, y1);
}

function draw() {
	limit += 2;
	if (limit > grid.length) {
		limit = grid.length;
	}
	var logo;
	for (var i = 0; i < limit; i++) {
		logo = grid[i];
		xr_logo(logo.x, logo.y, colors[logo.color]);
	}
}

$(window).resize(setup);

function setup_join_form() {
	if ($('#join form').length == 0) {
		return;
	}
	$('#join form').submit(function(e) {
		e.preventDefault();
		$('#join-feedback').removeClass('hidden');
		$('#join-feedback').html('Sending your details...');

		var values = $(this).serialize() + '&ajax=1';
		$.post('/wp-admin/admin-ajax.php?action=xrcr_join', values, function(rsp) {
			if (rsp && rsp.ok) {
				$('#join-feedback').html(rsp.feedback);
				var h = $('#join .form-step1').height();
				$('#join .form-step1').height(h);
				$('#join .form-step1').css('opacity', 1);
				$('#join .form-step1').hide(500);
			} else {
				$('#join-feedback').html('Oops, there was a problem saving your details.');
			}
		});
	});
}

$(document).ready(function() {
	setup_join_form();
});
