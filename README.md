completely awesome & naturally amazing
-------------------------------------------

cana is an mvc php framework for rapid object oriented development. it combines existing php framework fundamentals found in frameworks such as zend, and combines it with easy to use, extended object support found in ruby, python, and jquery. cana's name speaks for itself.


some cool shit you can do
----------------------

grab an object from the database and cache it in mem

    $shot = new BigBrother_Shot('DEV_DSM_000');


or do the same thing but with auto object creation, auto class aliasing, and removing the unnecessary quotes

    $shot = Shot::o(DEV_DSM_000);


call a function on multiple database objects

    Shot::o(DEV_DSM_000,DEV_DSM_100)->delete();


set a property on multiple objects and save them back to the db

    echo Staff::o(DSM,MPR,SSW)->s('permission','MANAGER')->save();
    

set multiple properties on an object an save it

    Shot::o(DEV_DSM_000)->s([
        'date_due' => '2012-01-01',
        'id_deliv_group' => 'LUMA'
    ])->save();
    

output a database object as json

    echo Shot::o(DEV_DSM_000)->json();



or output multiple database objects as a single json object

    echo Shot::o(DEV_DSM_000,534)->json();
    

modify several objects properties from a db query

    c::db()->get('select * from shots where id_project="marvel"')->producer = 'DSM';
    

iterate using the db object

    foreach (c::db()->get('select * from shots where id_project="marvel"') as $shot) {
        print_r($shot);
    }


set the producer property of two objects, then access those dbos from their cache and output their producer property

    Shot::o(DEV_DSM_000,534)->producer = 'DSM';
    echo Shot::o(DEV_DSM_000)->producer;
    echo Shot::o(534)->producer;
    

this filter would be the same as this sql ((id_project=dev AND shot_name=DSV_DSM_000) OR (id_shot=534))

    echo Shot::o(
            ['shot_name' => 'peanuts', 'id_project' => 'dev'],
            '{"shot_name": "BACON"}',
            DEV_DSM_000,
            '534'
        )->filter(
            ['id_project' => 'dev', 'shot_name' => 'DEV_DSM_000'],
            ['id_shot' => '534']
        );
    

filter a set of objects to set the producer property on only specific items, then return the full set

    $shots = Shot::o(['shot_name' => 'peanuts'],'{"shot_name": "BACON"}',DEV_DSM_000,534)
        ->filter('id_project','devs')
        ->set('producer','DSM')
        ->parent();
    

access the last items in the set

    echo Shot::o('{"shot_name": "BACON"}',DEV_DSM_000,'534')->eq(-1);
    

constructing a set with several arguments, arrays, or sets, will merge the items insite

    i::o(Shot::o(534),Shot_Element::o(4944))->each(function($key, $item) {
        echo $this;
    });
    

create a set from an already existing array sand iterate through them using foreach

    foreach (i::o(Project::o('marvel')->shots(), Project::o('uw4')->shots()) as $shot) {
        echo $shot;
    }
    

use the query method to build a set, then loop through each item

    Shot_Element::q('
        select elements.* from tasks
        left join elements on elements.id_shot_element=tasks.id_shot_element
        left join shots on shots.id_shot=elements.id_shot
        left join projects on projects.id_project=shots.id_project
        where id_element="PLATE"
        and id_task_status="NONE"
        and id_elem_status!="FINL"
        and elements.id_deliv_group!="CUT"
        and (elements.id_staff="TDA" or elements.id_staff="JPR")
        and shots.active=1
        and projects.active=1
    ')->e(function($item) { 
        // do some cool stuff here
     });
    

create the test function on the base cana object. this puts the function easily accesable in the global scope

    c::extend(['test' => function() {
        return 'this is a test';
    }]);
    echo c::test();
    

extend a class with a function that can access the object

    Staff::extend(['simpleFunc' => function($me, $in) {
        echo get_class($me).' '.$in;
    }]);
    Staff::o(DSM)->simpleFunc('got this');
    

extend a class to return a set result

    Staff::extend(['devices' => function($me) {
        return $me->q('select * from device where id_staff="'.$me->id_staff.'"');
    }]);
    Staff::o(DSM)->devices()->each(function($key, $item) {
        print_r($item);
    });






other stuff
---------

cana is named after my new favorite bar here in LA -- Caña Rum Bar


