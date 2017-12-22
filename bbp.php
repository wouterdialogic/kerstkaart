<?php
require 'vendor/autoload.php';
require 'config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Gorder\Entry as Entry;

$all_entries = Entry::all();

$number_of_entries = Entry::all()->count();

echo "<Br>entries: ".$number_of_entries;
//echo "<pre>";
//print_r($all_entries);
//print_r($_POST);
//echo "</pre>";

$unique_id = 0;
foreach ($all_entries as $key => $entry) {
  //echo "<Br>$key: ".$entry->text." - ".$entry->image_location;
  if (file_exists($entry->image_location)){
    list($width, $height) = getimagesize($entry->image_location);
    $all_entries[$key]["width"] = $width;
    $all_entries[$key]["height"] = $height;
    $entry->unique_id = $i;
    $i++;
  } else {
    unset($all_entries[$key]);
  }
  if (empty($width)) {

  } 
}

//for ($i=0; $i < sizeof($all_entries) ; $i++) {
//  $all_entries[$i]["unique_id"] = $i;
$new_count = sizeof($all_entries);
echo "count: ".$new_count;
//echo "<pre>";
//print_r(compact('all_entries'));
//
//echo "</pre>";
?>

<!DOCTYPE html>
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
  widht: 100%;
  height: 100%;
}

</style>
<img src="https://static.wixstatic.com/media/1db3d5_e551f3fd1f014dce9b1b98706686961b.jpg_srz_221_221_85_22_0.50_1.20_0.00_jpg_srz" alt="Smiley face" height="42" width="42">

<button onClick="button_click()">click me!</button>
<center>
    <svg width="1000" height="1000">
    
         <?php foreach ($all_entries as $key => $entry) {
  echo '<defs id="mdef">
         <pattern id="'.$entry->unique_id.'" x="0" y="0" height="1" width="1">';
   echo '<image x="-40" y="-40" width="'.$entry->width.'" height="'.$entry->height.'" xlink:href="'.$entry->image_location.'"></image>';
 echo '</pattern>
       </defs>' ;

 } ?>

    <?php 
   //echo '<defs id="mdef">
   //    <pattern id="1" x="0" y="0" height="1" width="1">';
   //    echo '<image x="-55" y="-40" width="250" height="250" xlink:href="img-1513942468-308.png"></image>';
   //    echo '</pattern>
   //  </defs>';    

   //  echo '<defs id="mdef">
   //    <pattern id="2" x="0" y="0" height="1" width="1">';
   //    echo '<image x="-55" y="-40" width="250" height="250" xlink:href="img-1513942629-975.png"></image>';
   //    echo '</pattern>
   //   </defs>';
 ?>

    <!--  <defs id="mdef">
        <pattern id="1" x="0" y="0" height="1" width="1">
          <image x="0" y="0" width="100" height="100" xlink:href="https://static.wixstatic.com/media/1db3d5_e551f3fd1f014dce9b1b98706686961b.jpg_srz_221_221_85_22_0.50_1.20_0.00_jpg_srz"></image>
          <image x="-55" y="-40" width="250" height="250" xlink:href="img-1513942489-669.png"></image>
        </pattern>
      </defs> -->
  </svg>
    <!-- <svg></svg> -->
</center>
<button onClick="update2()">click</button>
<div class="wouter">wouter</div>
<a href=#test>test</a>
<script src="https://d3js.org/d3.v4.js"></script>
<script>

    initial_amount_of_circles = <?php echo $new_count ?> ;
    max_amount_of_circles_on_screen = 17;
    min_amount_of_circles_on_screen = 11;
    time_interval_in_ms_of_transition = 2500;
    transition_duration = 2500;
    animate = 1;
    mouse_coords = null;

    var svg = d3.select("svg"),
    width = +svg.attr("width"),
    height = +svg.attr("height");
    var g = svg.append("g")
    .attr("class", "group_circles");

    spheres = create_this_many_objects(initial_amount_of_circles);

    selection_of_spheres = select_a_few_circles(spheres, min_amount_of_circles_on_screen, 10);

//initial_drawing
//draw_circles(selection_of_spheres);

//Grab a random sample of letters from the alphabet, in alphabetical order.
if (animate) {
    t = d3.interval(function() {
        if (check_if_max_number_of_circles_on_screen()) {

            remove_a_few_and_add_one_circle();

        }

        //transfer_one_circle_to_selection();
        transfer_a_few_circles();

        if (animate) {
            draw_circles(selection_of_spheres);
        }
     //giveCircles(d3.shuffle(circles)
     //    .slice(0, Math.floor(Math.random() * 5))
     //    .sort());
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
    amount_to_remove = return_number_between(2, 4);

    for (i=0; i<amount_to_remove; i++) {
        spheres.push( selection_of_spheres.splice([Math.floor(Math.random() * selection_of_spheres.length)], 1)[0] );
    }

    selection_of_spheres.push( spheres.splice([Math.floor(Math.random() * spheres.length)], 1)[0] );

}

function transfer_a_few_circles() {
    amount_to_add = return_number_between(1, 3);

    for (i=0; i<amount_to_add; i++) {
        selection_of_spheres.push( spheres.splice([Math.floor(Math.random() * spheres.length)], 1)[0] );
    }
}

function transfer_one_circle_to_selection() {
    selection_of_spheres.push( spheres.splice([Math.floor(Math.random() * spheres.length)], 1)[0] );
}

function return_number_between(min,max)
{
    return Math.floor( Math.random() * (max-min+1) + min );
}

function create_this_many_objects(this_many) {
    output = [];

    for (i=0; i<this_many; i++) {
        console.log(i);
        square = {
            "x": return_number_between(50, 450),
            "y": return_number_between(50, 450),
            "cx": return_number_between(100, 800),
            "cy": return_number_between(100, 800),
            "index": i,
            "size": return_number_between(45, 70),
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

function do_random_animation(d = null) {
   animation_type = Math.floor(Math.random() * 100);
   if (animation_type < 50) {

    } else {

    }
}

function draw_circles(data) {
    

    var t = determine_circle_status();
    g = d3.select("g");

    //console.log(countertrello);


    var circles = g.selectAll("circle.update, circle.exit, circle.enter")
    
      //.data(data, function(d) {return d;}); //THIS IS QUITE INTERESTING
    
      .data(data); //THIS IS QUITE INTERESTING

    circles.exit()
    .attr("class", "exit")
    .transition(t)
    //.attr("cx", function(d, i) {return i*55})
    //.attr("cy", function(d) {return d.y})
    .attr("r", function(d) {
        //console.log(this.transitionBusy); 
        //console.log(d.transitionBusy); 
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
      .style("fill", function(d, i) {return 'url(#'+d.index+')'})
      .attr("cx", function(d, i) {return return_calculated_coords(d, i)})
      .attr("cy", function(d, i) {return  d.cy})
      // .attr("fill", "pink")
      .attr("r", 0)
      .transition(t)
      //.attr("cy", function(d) {return d.y})
      .style("fill-opacity", 1)
      .attr("r", function(d) {
        //console.log(this.transitionBusy);
    // this.transitionBusy
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
      //.attr("dy", ".35em")
      //.attr("fill", "green")
       .style("fill", function(d, i) {
        console.log("index"+d.index);
return 'url(#'+d.index+')'})
        
      //.attr("xlink:href", "https://static.wixstatic.com/media/1db3d5_e551f3fd1f014dce9b1b98706686961b.jpg_srz_221_221_85_22_0.50_1.20_0.00_jpg_srz")
      //.attr("cy", -60)
      .on("mouseover", function (d,i) {
        currentObject = d3.select(this);
        console.log("mouse");
        mouse_coords = d3.mouse(this);
        console.log(d3.mouse(this));

        //d3.select(this).style("stroke","black").style("stroke-width", "4.5px").style("opacity","1.0");
        d3.select(this).style("opacity","1.0");
        d3.select(this).attr("class", "sexy");
        d3.select(this).transition().duration(1000).attr("r", 
            function(d) {
                return d.size * 1.2
            });

    })
      .on("mouseout", function (d,i) {
        mouse_coords = null;
        //d3.select(this).attr("class", "enter");
        //d3.select(this).style("stroke","lightgrey").style("stroke-width", "3.5px").style("opacity","0.55")
        d3.select(this).style("opacity","0.55")
                d3.select(this).transition().duration(3000).attr("r", 
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
    console.log(mouse_coords);
    return d.cx;
  }

  function return_transition(input) {
    console.log(input);
    return d3.transition().duration(transition_duration);
}

</script>