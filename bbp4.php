<?php
require 'vendor/autoload.php';
require 'config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Gorder\Entry as Entry;

$all_entries = Entry::all();
$number_of_entries = Entry::all()->count();

$url_speed = (!empty($_GET['speed']) ? $_GET['speed'] : 3000) ;
$url_dialogic = (!empty($_GET['dialogic']) ? $_GET['dialogic'] : false) ;

echo "<Br>url_speed: ".$url_speed;
echo "<Br>url_dialogic: ".$url_dialogic;
echo "<Br>entries: ".$number_of_entries;

$unique_id = 0;
foreach ($all_entries as $key => $entry) {
  if (file_exists($entry->image_location)){
    //if ($key == 1 or $key == 2 or $key == 3) {
    list($width, $height) = getimagesize($entry->image_location);
    $all_entries[$key]["width"] = $width;
    $all_entries[$key]["height"] = $height;
    $entry->unique_id = $unique_id;
    $unique_id++;
    //} else {}
  } else {
    unset($all_entries[$key]);
  }
}

//echo "<pre>";
//print_r($all_entries);
//echo "</pre>";

$new_count = sizeof($all_entries);
echo "count: ".$new_count;
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
  width: 90%;
  height: 90%;
}

</style>
<img src="https://static.wixstatic.com/media/1db3d5_e551f3fd1f014dce9b1b98706686961b.jpg_srz_221_221_85_22_0.50_1.20_0.00_jpg_srz" alt="Smiley face" height="42" width="42">

<button onClick="button_click()">click me!</button>
<center>
    <svg width="1000" height="700">
    
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

</center>
<?php if ($url_dialogic == "waar") { ?>
circles:
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
fading duration<input id="fading_duration" type="number">
<?php } ?>
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
    console.log(max_amount_of_circles_on_screen);
    console.log(typeof(max_amount_of_circles_on_screen));
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
    console.log(min_amount_of_circles_on_screen);
});
$("#add_min" ).change(function() {

    add_min = parseInt($("#add_min" ).val());
    if (add_min > difference) {
      add_min = difference;
    }
    if (add_min > add_max) {
      add_min = add_max;
    }
    console.log(add_min);
});  
$("#add_max" ).change(function() {
    add_max = parseInt($("#add_max" ).val());
    if (add_max > difference) {
      add_max = difference;
    }
    if (add_max < add_min) {
      add_max = add_min;
    }
    console.log(add_max);
});
$("#remove_min" ).change(function() {
    remove_min = parseInt($("#remove_min" ).val());
    if (remove_min > remove_max) {
      remove_min = remove_max;
    }
    console.log(remove_min);
});  
$("#remove_max" ).change(function() {
    remove_max = parseInt($("#remove_max" ).val());
    if (remove_max < remove_min) {
      remove_max = remove_min;
    }
    console.log(remove_max);
});  
$("#time_interval_in_ms_of_transition" ).change(function() {
    time_interval_in_ms_of_transition = parseInt($("#time_interval_in_ms_of_transition" ).val());
    console.log(time_interval_in_ms_of_transition);
});  
$("#transition_duration" ).change(function() {
    transition_duration = parseInt($("#transition_duration").val());
    console.log(transition_duration);
});  
$("#fading_duration" ).change(function() {
    fading_duration = parseInt($("#fading_duration").val());
    console.log(fading_duration);
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

//initial_drawing
//draw_circles(selection_of_spheres);

//Grab a random sample of letters from the alphabet, in alphabetical order.
if (animate) {
    t = d3.interval(function() {
        //var a = ["a", "b", "c"];
            console.log(t);
        //selection_of_spheres.forEach(function(entry) {
       // });
        if (check_if_max_number_of_circles_on_screen()) {
            remove_a_few_and_add_one_circle();
        }
        //transfer_one_circle_to_selection();
        transfer_a_few_circles();

        if (animate) {
            draw_circles(selection_of_spheres);
        }
     //d3.shuffle(selection_of_spheres)
     //    .slice(0, Math.floor(Math.random() * 5))
     //    .sort();
 }, time_interval_in_ms_of_transition);
}

function check_if_max_number_of_circles_on_screen() {
    if (max_amount_of_circles_on_screen <= selection_of_spheres.length) {
        console.log("MORE THAN ENOUGH!");
        return true;
    }
}

function button_click() {
  console.log("click me!");
  console.log(mouse_coords);
}

function remove_a_few_and_add_one_circle() {
    //console.log("selection: "+selection_of_spheres.length);
    //console.log("spheres: "+spheres.length);
    amount_to_remove = return_number_between(remove_min, remove_max);
    console.log("removing: "+amount_to_remove);
    for (i=0; i<amount_to_remove; i++) {
        if (selection_of_spheres.length > 1) {
            spheres.push( selection_of_spheres.splice([Math.floor(Math.random() * selection_of_spheres.length)], 1)[0] );
        }
    }

    //selection_of_spheres.push( spheres.splice([Math.floor(Math.random() * spheres.length)], 1)[0] );

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
    //console.log("");
    //console.log("add_min: "+add_min);
    //console.log("add_max: "+add_max);
    //console.log("");
    //console.log("amount_to_add: "+amount_to_add);
    //console.log("selection: "+selection_of_spheres.length);
    //console.log("spheres: "+spheres.length);
    console.log("adding: "+amount_to_add);
    while (max_amount_of_circles_on_screen < selection_of_spheres.length + amount_to_add) {
        //console.log("ey must remeve mohre");
        remove_a_few_circles();
    }

   // console.log("after removing: selection: "+selection_of_spheres.length);
   // console.log("after removing: spheres: "+spheres.length);

    for (i=0; i<amount_to_add; i++) {
        selection_of_spheres.push( spheres.splice([Math.floor(Math.random() * spheres.length)], 1)[0] );
    }
}

function transfer_one_circle_to_selection() {
    selection_of_spheres.push( spheres.splice([Math.floor(Math.random() * spheres.length)], 1)[0] );
}

function return_number_between(min,max)
{
  random_number = Math.floor( Math.random() * (max-min+1) + min );
  //console.log("min: "+min+" - max: "+max+" - gives: "+random_number);
    return random_number;
}

function create_this_many_objects(this_many) {
    output = [];

    for (i=0; i<this_many; i++) {
        //console.log(i);
        square = {
            "x": return_number_between(50, 450),
            "y": return_number_between(50, 450),
            "cx": return_number_between(100, 800),
            "cy": return_number_between(100, 800),
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
      //.attr("cx", function(d, i) {return  0})
      //.attr("cy", function(d, i) {return  d.cy})
      //.attr("cx", function(d, i) { return  d.cx})
      //.style("fill", "transparent")
       //.style("fill", "url(#6)")
      //.style("fill", function(d, i) {return 'url(#'+d.index+')'})
      //.attr("cx", function(d, i) {return return_calculated_coords(d, i)})
      .attr("cx", function(d, i) {
console.log(d);
        return d.cx})
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
       .style("fill", function(d, i) {
        //console.log("index"+d.index);
        return 'url(#'+d.index+')'})
        
      //.attr("xlink:href", "https://static.wixstatic.com/media/1db3d5_e551f3fd1f014dce9b1b98706686961b.jpg_srz_221_221_85_22_0.50_1.20_0.00_jpg_srz")
      //.attr("cy", -60)
      .style("stroke","black").style("stroke-width", "1px").style("opacity","1.0")
      .on("mouseover", function (d,i) {
        currentObject = d3.select(this);
        console.log("mouse");
        mouse_coords = d3.mouse(this);
        console.log(d3.mouse(this));

        //d3.select(this).style("stroke","black").style("stroke-width", "4.5px").style("opacity","1.0");
        d3.select(this).style("opacity","1.0");
        d3.select(this).attr("class", "sexy");

        d3.select(this).transition().duration(500)
        .attr("r", 
            function(d) {
                return d.size * 1.0
            })
        .style("stroke","red").style("stroke-width", "20px").style("opacity","1.0")
        .transition().duration(500)

        .style("stroke","green").style("stroke-width", "10px").style("opacity","1.0");



    })
      .on("mouseout", function (d,i) {
        mouse_coords = null;
        //d3.select(this).attr("class", "enter");
        //d3.select(this).style("stroke","lightgrey").style("stroke-width", "3.5px").style("opacity","0.55")
        d3.select(this).style("opacity","0.55")
                d3.select(this).transition().duration(fading_duration).attr("r", 
            function(d) {
                return 0; 
            }).remove();
            
    })

      .attr("cy", function(d, i) { return d.cy; })
      .attr("cx", function(d, i) { return d.cx; })
      //.style("fill-opacity", 1e-6)
      //.style("stroke", "black")
      //.style("opacity", .2)
      .attr("r", 0)
    //.transition(function(d) {return return_transition(input)})
    .transition(t)
    .attr("r", function(d) { 
        // console.log(this.transitionBusy);
        return d.size; })
    // .attr("cx", function(d, i) { 
    //     console.log(mouse_coords);
    //     return d.cx; })    
    .attr("cx", function(d, i) {return return_calculated_coords(d, i)})
    //.style("stroke", "white")
    //.style("opacity", .2)
    .attr("cy",  function(d, i) { return d.cy; });
      //.style("fill-opacity", 1);
  }

  function return_calculated_coords(d, i) {
    //console.log(mouse_coords);
    return d.cx;
  }

  function return_transition(input) {
    //console.log(input);
    return d3.transition().duration(transition_duration);
}

</script>