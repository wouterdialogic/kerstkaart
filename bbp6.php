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
//echo "<Br>url_speed: ".$url_speed;
//echo "<Br>url_dialogic: ".$url_dialogic;
//echo "<Br>entries: ".$number_of_entries;

$unique_id = 0;
foreach ($all_entries as $key => $entry) {
  if (file_exists($entry->image_location)){
    list($width, $height) = getimagesize($entry->image_location);
    $all_entries[$key]["width"] = $width;
    $all_entries[$key]["height"] = $height;
    $entry->unique_id = $unique_id;
    $unique_id++;
  } else {
    unset($all_entries[$key]);
  }
}

$new_count = sizeof($all_entries);
//echo "count: ".$new_count;
?>

<!DOCTYPE html>
<html>
<body>
<meta charset="utf-8">
<style>

text {
  font: bold 48px monospace;
}

.enter {
  /*fill: green;*/
}

.update {
 /*// fill: blue;*/
/*  border-style: solid;
  border: 1px;
  border-style: solid;   */
}

.barsEndlineText {
  fill: black;
  color: red;
}

.exit {
  fill: red;
}

.group_circles {
    fill: yellow;
}

.bar {
    background-color:   teal;
    display:    inline-block;   
    width:         20px;
    height: 74px;
}

svg {
    #position: relative;
    #top: -50%;
    margin: 0 auto;
    width: 80%;
    height: 800px;
    border: 1px solid orange;
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
    /*border: 1px solid orange;*/
}

</style>
<!-- <button onClick="button_click()">click me!</button> -->
<div class="container">
    <div class="helper">
      <center>
      <!-- <svg width="800" height="400"> -->
      <svg>
    
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
min<input id="min_amount_of_circles_on_screen" type="number" min="1">
max<input id="max_amount_of_circles_on_screen" type="number" min="2" max="<?php echo $new_count ?>">
<Br>
min<input id="add_min" type="number">
max<input id="add_max" type="number">
<Br>
min<input id="remove_min" type="number">
max<input id="remove_max" type="number">
<Br>speed (think big):
i dunno<input id="time_interval_in_ms_of_transition" type="number">
i really dunno<input id="transition_duration" type="number">
dunno<input id="fading_duration" type="number">
</center>
<!-- circles:
min on screen<input id="min_amount_of_circles_on_screen" type="number" min="1">
max on screen<input id="max_amount_of_circles_on_screen" type="number" min="2" max="<?php echo $new_count ?>">
<Br>add:
min to add<input id="add_min" type="number">
max to add<input id="add_max" type="number">
<Br>remove:
min to remove<input id="remove_min" type="number">
max to remove<input id="remove_max" type="number">
<Br>speed:
transition time<input id="time_interval_in_ms_of_transition" type="number">
transition duration<input id="transition_duration" type="number">
fading duration<input id="fading_duration" type="number"> -->
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

    initial_amount_of_circles = <?php echo $new_count ?> ;
    max_amount_of_circles_on_screen = 10;
    min_amount_of_circles_on_screen = 3;
    remove_min = 3;
    remove_max = 3;
    add_min = 1;
    add_max = 1;
    fading_duration = 1500;
    time_interval_in_ms_of_transition = <?php echo $url_speed ?>;
    transition_duration = <?php echo $url_speed ?>;
    animate = 1;
    mouse_coords = null;

    var svg = d3.select("svg"),
    width = +svg.attr("width"),
    height = +svg.attr("height");

    var g = svg.append("g")
    .attr("class", "group_circles");

    spheres = create_this_many_objects(initial_amount_of_circles);
    selection_of_spheres = select_a_few_circles(spheres, min_amount_of_circles_on_screen, max_amount_of_circles_on_screen);

//Grab a random sample of letters from the alphabet, in alphabetical order.
if (animate) {
    t = d3.interval(function() {

        if (check_if_max_number_of_circles_on_screen()) {
            remove_a_few_and_add_one_circle();
        }

        transfer_a_few_circles();

        draw_circles(selection_of_spheres);
            console.log("height: "+height);
    console.log("width: "+width);
 }, time_interval_in_ms_of_transition);
}

function check_if_max_number_of_circles_on_screen() {
    if (max_amount_of_circles_on_screen <= selection_of_spheres.length) {
        return true;
    }
}

//function button_click() {
//  console.log(mouse_coords);
//}

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

    for (i=0; i<this_many; i++) {
        square = {
            "x": return_number_between(50, 450),
            "y": return_number_between(50, 450),
            "cx": return_number_between(0, 1500),
            "cy": return_number_between(0, 750),
            "index": i,
            //"size": return_number_between(45, 70),
            "size": return_number_between(80, 98),
            "transitionBusy": 0,
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

switching = true;

function do_random_animation(d = null) {
   animation_type = Math.floor(Math.random() * 100);
   if (animation_type < 50) {
    } else {
    }
}

function draw_circles(data) {
    var t = determine_circle_status();
    g = d3.select("g");

    var circles = g.selectAll("circle.update, circle.exit, circle.enter")
    //.data(data, function(d) {return d;}); //THIS IS QUITE INTERESTING
    .data(data); //THIS IS QUITE INTERESTING

    //EXIT:
    circles.exit()
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
    circles.attr("class", "update")
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
  circles.enter().append("circle")
  .attr("class", "enter")
  .style("fill", function(d, i) {return 'url(#'+d.index+')'})
  .style("stroke","white").style("stroke-width", "1px").style("opacity","1.0")

      //MOUSEOVER
      .on("mouseover", function (d,i) {
        currentObject = d3.select(this);
        mouse_coords = d3.mouse(this);
        d3.select(this).style("opacity","1.0");
        d3.select(this).attr("class", "sexy");
        d3.select(this).transition().duration(500)
        .attr("r", 
          function(d) {
            return d.size * 1.0
          })
        .style("stroke","red").style("stroke-width", "2px").style("opacity","1.0")
        .transition().duration(500)
        .style("stroke","green").style("stroke-width", "2px").style("opacity","1.0");
      })

      //MOUSEOUT
      .on("mouseout", function (d,i) {
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
      .transition(t)
      .attr("r", function(d) { return d.size; })  
      .attr("cx", function(d, i) {return return_calculated_coords(d, i)})
      //.style("stroke", "black")
      .attr("cy",  function(d, i) { return d.cy; });
  }

  function return_calculated_coords(d, i) {
    //console.log(mouse_coords);
    return d.cx;
  }

</script>