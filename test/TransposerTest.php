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
        $songText = "C D E F G A B";
        $inKey = "C";
        
        $transposer = new ChuManFu\Transposer($songText);
        $transposer->loadSong();
        $transposedSong = $transposer->transpose($inKey, $newKey);
        $this->assertEquals($notes, $transposedSong);
    }
    
    public function inputAllSimpleScales()
    {
        $scales = array(); 
        $scales[] = array('A', 'A B C# D E F# Ab');
        $scales[] = array('Bb', 'Bb C D Eb F G A');
        $scales[] = array('B', 'B C# Eb E F# Ab Bb');
        $scales[] = array('C', 'C D E F G A B');
        $scales[] = array('C#', 'C# Eb F F# Ab Bb C');
        $scales[] = array('D', 'D E F# G A B C#');
        $scales[] = array('Eb', 'Eb F G Ab Bb C D');
        $scales[] = array('E', 'E F# Ab A B C# Eb');
        $scales[] = array('F', 'F G A Bb C D E');
        $scales[] = array('F#', 'F# Ab Bb B C# Eb F');
        $scales[] = array('G', 'G A B C D E F#');
        $scales[] = array('Ab', 'Ab Bb C C# Eb F G');
        
        return $scales;
    }

}
