<?php

class TransposerTest extends PHPUnit_Framework_TestCase
{

    public function testGetNotesInScale()
    {

        $transposer = new ChuManFu\Transposer();
        
        $scaleNotes = "A,B,C,D,E,F,G";
        
        foreach(explode(",", $scaleNotes) as $scale)
        {        
            $notes = $transposer->getNotesInScale($scale);

            $this->assertEquals(count($notes), 12);
        }
        
    }
    
    /**
     * @dataProvider inputAllSimpleScales
     */
    public function testSimpleTranspose($newKey, $notes)
    {
        $songText = "A B C D E F G";
        $inKey = "C";
        
        $transposer = new ChuManFu\Transposer($songText);
        $transposer->loadSong();
        $transposedSong = $transposer->transpose($inKey, $newKey);
        $this->assertEquals($notes, $transposedSong);
    }
    
    public function inputAllSimpleScales()
    {
        $scales = array(); 
        $scales[] = array('A', 'F# G# A B C# D E');
        $scales[] = array('B', 'G# A# B C# Eb E F#');
        $scales[] = array('C', 'A B C D E F G');
        $scales[] = array('D', 'B C# D E F# G A');
        $scales[] = array('E', 'C# Eb E F# G# A B');
        $scales[] = array('F', 'D E F G A A# C');
        $scales[] = array('G', 'E F# G A B C D');
        
        return $scales;
    }

}
