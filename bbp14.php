<?php
require 'vendor/autoload.php';
require 'config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Gorder\Entry as Entry;

$all_entries = Entry::all();
$number_of_entries = Entry::all()->count();

$url_speed = (!empty($_GET['speed']) ? $_GET['speed'] : 3000) ;
$url_dialogic = (!empty($_GET['dialogic']) ? $_GET['dialogic'] : false) ;

if ($url_dialogic == "true") {echo "true is niet waar";} 

$unique_id = 0;
foreach ($all_entries as $key => $entry) {
  if (file_exists($entry->image_location)){
    list($width, $height) = getimagesize($entry->image_location);
    $all_entries[$key]["width"] = $width;
    $all_entries[$key]["height"] = $height;
    $entry->unique_id = $unique_id;
    $unique_id++;
    if (empty($all_entries[$key]["text"])) {
      $all_entries[$key]["text"] = '';
    }
  } else {
    unset($all_entries[$key]);
  }
}

//echo "<pre>";
//print_r($all_entries);
//echo "</pre>";

$all_entries_json = json_encode($all_entries);
$new_count = sizeof($all_entries);
?>

<!DOCTYPE html> 
<html lang ="en">
    <head>
        <meta charset="UTF-8" >
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Wishes</title>
        <meta name="description" content="So good">
        <meta property="og:title" content="Christmas wishes">
        <meta property="og:type" content="website">
        <meta property="og:url" content="http://dialogiconderzoek.nl/wouter/kk4/">
        <meta property="og:description" content="A christmas card website">
        <meta property="og:image" content="christmas-icon-tree-128.png">
        <link rel="icon" href="christmas-icon-tree-128.png"/>
    </head>

<style>

@font-face { font-family: Delicious; src: url('CaviarDreams.ttf'); } 

text {
  font-family: Delicious, sans-serif;
}

body {
    overflow:hidden;
}

.exit {
  fill: red;
}

.group_circles {
    fill: yellow;
}

svg {
    #position: relative;
    #top: -50%;
    margin: 0 auto;
    width: 90%;
    height: 800px;
    border: 1px solid #eeeeee;
}

.container {
    display: table;
    height: 100%;
    position: absolute;
    overflow: hidden;
    width: 100%;
}

.helper {
    #position: absolute;
    #top: 50%;
    display: table-cell;
    vertical-align: middle;
}

.content {
    #position: relative;
    #top: -50%;
    margin: 0 auto;
    width: 200px;
}

</style>
<body>

<div class="container">
    <div class="helper" id="helper">
      <center>
      <svg id="woutersvg">
    
      <?php foreach ($all_entries as $key => $entry) {
      echo '
      <defs id="mdef">
      <pattern id="'.$entry->unique_id.'" x="0" y="0" height="1" width="1">';
      echo '
      <image x="-5" y="-5" width="'.$entry->width.'" height="'.$entry->height.'" xlink:href="'.$entry->image_location.'"></image>';
      echo '    
      </pattern>
      </defs>' ;
      } ?>

      </svg>
      </center><Br>
<center>
<?php if ($url_dialogic == "waar") { ?>
min circles on screen<input id="min_amount_of_circles_on_screen" type="number" min="1">
max circles on screen<input id="max_amount_of_circles_on_screen" type="number" min="2" max="<?php echo $new_count ?>">
<Br>add:
min to add<input id="add_min" type="number">
max to add<input id="add_max" type="number">
<Br>remove:
min to remove<input id="remove_min" type="number">
max to remove<input id="remove_max" type="number">
<Br>speed:
transition time<input id="time_interval_in_ms_of_transition" type="number">
transition duration<input id="transition_duration" type="number">
fading duration<input id="fading_duration" type="number">
<?php } ?>
</div>
</div>

</body>
</html>
<script src="https://d3js.org/d3.v4.js"></script>
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<script>

svgwidth = document.getElementById('helper').offsetWidth;
svgheight = document.getElementById('helper').offsetHeight;
//d3.select('body').append("p")
//    .text("w: "+svgwidth);
//    d3.select('body').append("p")
//    .text("h: "+svgheight);

//getting data from php
all_entries_json = <?php echo $all_entries_json; ?> ;
//console.log("w: "+svgwidth);
//console.log("h: "+svgheight);

//change handlers:
$("#max_amount_of_circles_on_screen" ).change(function() {
    max_amount_of_circles_on_screen = parseInt($("#max_amount_of_circles_on_screen" ).val());
    if (max_amount_of_circles_on_screen <= min_amount_of_circles_on_screen) {
      max_amount_of_circles_on_screen = min_amount_of_circles_on_screen + 1;
    }    
    if (max_amount_of_circles_on_screen > initial_amount_of_circles) {
      max_amount_of_circles_on_screen = initial_amount_of_circles ;
    }
    difference = max_amount_of_circles_on_screen - min_amount_of_circles_on_screen;
});  
$("#min_amount_of_circles_on_screen" ).change(function() {
    min_amount_of_circles_on_screen = parseInt($("#min_amount_of_circles_on_screen" ).val());
    if (min_amount_of_circles_on_screen > max_amount_of_circles_on_screen) {
      min_amount_of_circles_on_screen = max_amount_of_circles_on_screen - 1;
    }
    if (min_amount_of_circles_on_screen < 1) {
      min_amount_of_circles_on_screen = 1;
    }
    difference = max_amount_of_circles_on_screen - min_amount_of_circles_on_screen;
});
$("#add_min" ).change(function() {

    add_min = parseInt($("#add_min" ).val());
    if (add_min > difference) {
      add_min = difference;
    }
    if (add_min > add_max) {
      add_min = add_max;
    }
});  
$("#add_max" ).change(function() {
    add_max = parseInt($("#add_max" ).val());
    if (add_max > difference) {
      add_max = difference;
    }
    if (add_max < add_min) {
      add_max = add_min;
    }
});
$("#remove_min" ).change(function() {
    remove_min = parseInt($("#remove_min" ).val());
    if (remove_min > remove_max) {
      remove_min = remove_max;
    }
});  
$("#remove_max" ).change(function() {
    remove_max = parseInt($("#remove_max" ).val());
    if (remove_max < remove_min) {
      remove_max = remove_min;
    }
});  
$("#time_interval_in_ms_of_transition" ).change(function() {
    time_interval_in_ms_of_transition = parseInt($("#time_interval_in_ms_of_transition" ).val());
});  
$("#transition_duration" ).change(function() {
    transition_duration = parseInt($("#transition_duration").val());
});  
$("#fading_duration" ).change(function() {
    fading_duration = parseInt($("#fading_duration").val());
});

//setting up:
initial_amount_of_circles = <?php echo $new_count ?> ;
min_amount_of_circles_on_screen = 4;
max_amount_of_circles_on_screen = 12;
remove_min = 3;
remove_max = 6;
add_min = 1;
add_max = 3;
fading_duration = 1500;
time_interval_in_ms_of_transition = <?php echo $url_speed ?>;
transition_duration = <?php echo $url_speed ?>;
animate = 1;
mouse_coords = null;

//checks
if (initial_amount_of_circles < min_amount_of_circles_on_screen) {
  min_amount_of_circles_on_screen = initial_amount_of_circles -1;
}    
if (initial_amount_of_circles < max_amount_of_circles_on_screen) {
  max_amount_of_circles_on_screen = initial_amount_of_circles;
}
console.log(min_amount_of_circles_on_screen)
console.log(max_amount_of_circles_on_screen)

//setting up the drawing board
var svg = d3.select("svg"),
width = +svg.attr("width"),
height = +svg.attr("height");
console.log("height: "+svgheight);
console.log("width: "+svgwidth);
var g = svg.append("g")
.attr("class", "group_circles");

//creating circles and selecting a few of them
spheres = create_this_many_objects(initial_amount_of_circles);
selection_of_spheres = select_a_few_circles(spheres, 3, 5);

//keep this, maybe play with this later
//transfer_a_few_circles();
//draw_circles(selection_of_spheres);
//transfer_a_few_circles();
draw_circles(selection_of_spheres);

//This loops and makes the application work
//there are 2 arrays, 1 with current circles on screen, 1 with the other circles.
//objects are sliced / pushed from one to another
if (animate) {
    t = d3.interval(function() {

        if (check_if_max_number_of_circles_on_screen()) {
            remove_a_few_and_add_one_circle();
        }

        transfer_a_few_circles();

        draw_circles(selection_of_spheres);

 }, time_interval_in_ms_of_transition);
}

function check_if_max_number_of_circles_on_screen() {
    if (max_amount_of_circles_on_screen <= selection_of_spheres.length) {
        return true;
    }
}


function remove_a_few_and_add_one_circle() {
    remove_a_few_circles();
}

function remove_a_few_circles() {
    amount_to_remove = return_number_between(remove_min, remove_max);
    console.log("removing: "+amount_to_remove);
    for (i=0; i<amount_to_remove; i++) {
      if (selection_of_spheres.length > 1) {
        spheres.push( selection_of_spheres.splice([Math.floor(Math.random() * selection_of_spheres.length)], 1)[0] );
      }
    }
}

function transfer_a_few_circles() {
    amount_to_add = return_number_between(add_min, add_max);

    console.log("adding: "+amount_to_add);
    while (max_amount_of_circles_on_screen < selection_of_spheres.length + amount_to_add) {
        remove_a_few_circles();
    }

    for (i=0; i<amount_to_add; i++) {
        selection_of_spheres.push( spheres.splice([Math.floor(Math.random() * spheres.length)], 1)[0] );
    }
}

function return_number_between(min,max)
{
    random_number = Math.floor( Math.random() * (max-min+1) + min );
    return random_number;
}

function create_this_many_objects(this_many) {
    output = [];

    //setting "global" properties with some margins for larger text / images
    svg_height = svgheight;
    svg_width = svgwidth;
    text_margin_left = 300;
    text_margin_right = 300;
    text_margin_top = 100;
    text_margin_bottom = 300;
    picture_margin = 150;

    x_min = 0;
    x_max = svg_width;
    y_min = 0;
    y_max = svg_height;

    cx_min = 0 + picture_margin;
    cx_max = svg_width - picture_margin - 100;
    cy_min = 0 + picture_margin;
    cy_max = svg_height - picture_margin - 100;

    textx_min = 0 + text_margin_left;
    textx_max = svg_width - text_margin_right;
    texty_min = 0 + text_margin_top;
    texty_max = svg_height - text_margin_bottom;

    for (i=0; i<this_many; i++) {
        square = {
            "x": return_number_between(x_min, x_max),
            "y": return_number_between(y_min, y_max),
            "cx": return_number_between(cx_min, cx_max),
            "cy": return_number_between(cy_min, cy_max),
            "textx": return_number_between(textx_min, textx_max),
            "texty": return_number_between(texty_min, texty_max),
            "index": i,
            //"size": return_number_between(45, 70),
            "size": return_number_between(80, 98),
            "transitionBusy": 0,
            "text": capitalizeFirstLetter(all_entries_json[i]["text"]),
            "other": return_number_between(5, 50)
        };
        output.push(square);
    }

    console.log(output);

    return output; 
}

function select_a_few_circles(input, min, max) {
    amount_of_input = return_number_between(min, max);
    output = [];

    for (i=0; i<amount_of_input; i++) {
        this_one = input.splice([Math.floor(Math.random() * input.length)], 1)[0];
        output.push( this_one );
    }

    return output;
}

function determine_circle_status(input = null) {
    return d3.transition().duration(transition_duration);
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function draw_circles(data) {
  console.log("drawing")
    var t = determine_circle_status();
    g = d3.select("g");

    var circles2 = g.selectAll("circle.update, circle.exit, circle.enter")
    //.data(data, function(d) {return d;}); //THIS IS QUITE INTERESTING
    .data(data); //THIS IS QUITE INTERESTING

    var circles = circles2.enter()
      .append("g")
      //.attr("transform", function(d){return "translate(200,200)"});

    //EXIT:
    circles2.exit()
    .attr("class", "exit")
    .transition(t)
    .attr("r", function(d) {
        if (d.busy == true) {
        } else {
        return 0;
        }
    })
    .style("fill-opacity", function(d) {
        if (d.busy == true) {
            return 1;
        } else {
            return 1e-6;
        }
    })
    .remove(false);

    // UPDATE old elements present in new data.
    circles2.attr("class", "update")
      //.style("fill", function(d, i) {return 'url(#'+d.index+')'})
      .attr("cx", function(d, i) {return d.cx})
      .attr("cy", function(d, i) {return  d.cy})
      .attr("r", 0)
      .transition(t)
      .style("fill-opacity", 1)
      .attr("r", function(d) {
    return d.size})
      .attr("cy", function(d, i) {
        return  d.cy
    })
      .attr("cx", function(d, i) { 
        return  d.cx}
        );

  // ENTER new elements present in new data.
  circles.append("circle")
  .attr("class", "enter")
  .style("fill", function(d, i) {return 'url(#'+d.index+')'})
  .style("stroke","white").style("stroke-width", "1px").style("opacity","1.0");

      //MOUSEOVER
      circles2.on("mouseover", function (d,i) {
        mouse_coords = d3.mouse(this);
        d3.select(this).style("opacity","1.0");
        d3.select(this).attr("class", "sexy");
       
        //TEXT
        d3.select(this.parentNode).append("text")
        .attr("x", function(d) {return d.textx})
        .attr("y", function(d){return d.texty})
        .style("fill-opacity", 0)
        .attr("fill", function(d){return "lightgrey"})
        .text(function(d){return  d.text})
        
        //TEXT TRANSITION 1
        .transition().duration(500)
        .style("fill-opacity", 1)
        .style("font-size", "500%")

        .attr("x", function(d) {return d.textx - 200})
      
      //TEXT TRANSITION 2
      .transition().duration(3000)
      .style("fill-opacity", 0)
      .style("font-size", "5000%")
      .attr("x", function(d) { return d.textx - 900})
      .remove();

        //CIRCLE MOUSEOVER ANIMATION 1
        d3.select(this).transition().duration(500)
        .attr("r", 
          function(d) {
            return d.size * 1.0
          })
        .style("stroke","red").style("stroke-width", "2px").style("opacity","1.0")

        //CIRCLE MOUSEOVER ANIMATION 2
        .transition().duration(500)
        .style("stroke","green").style("stroke-width", "2px").style("opacity","1.0")
      });

      //MOUSEOUT
      circles2.on("mouseout", function (d,i) {
        mouse_coords = null;
        d3.select(this).style("opacity","0.55")
        d3.select(this).transition().duration(fading_duration).attr("r", 
          function(d) {
            return 0; 
          }).remove();
      })
      .attr("cy", function(d, i) { return d.cy; })
      .attr("cx", function(d, i) { return d.cx; })
      .attr("r", 0)

      //NOT SURE, CHECK LATER IF INTERESTED...
      .transition(t)
      .attr("r", function(d) { return d.size; })  
      .attr("cx", function(d, i) {return return_calculated_coords(d, i)})
      //.style("stroke", "black")
      .attr("cy",  function(d, i) { return d.cy; });
  }

  function return_calculated_coords(d, i) {
    return d.cx;
  }

</script>