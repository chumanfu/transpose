<?php

namespace ChuManFu;

use ChuManFu\TransposerException;

class Transposer
{
    private $scale = array('C',
        array('C#', 'Db'),
        'D',
        array('Eb', 'D#'),
        'E',
        'F',
        array('F#', 'Gb'),
        'G',
        array('Ab', 'G#'),
        'A',
        array('Bb', 'A#'),
        'B');
    private $search = '`([ABCDEFG][b#]?(?=\s?(?![a-zH-Z])|(?=(2|5|6|7|9|11|13|6\/9|7\-5|7\-9|7\#5|7\#9|7‌​\+5|7\+9|7b5|7b9|7sus2|7sus4|add2|add4|add9|aug|dim|dim7|m\|maj7|m6|m7|m7b5|m9|m1‌​1|m13|maj7|maj9|maj11|maj13|mb5|m|sus|sus2|sus4|\))(?=(\s|\/)))|(?=(\/|\.|-|\(|\)))))`';
    private $song;
    private $formattedChords = array();
    private $replacementChords = array();

    public function __construct($song = '')
    {
        if ($song != '')
        {
            $this->song = $song;
        }
    }

    public function getNotesInScale($scale)
    {
        $notesInScale = array();
        $canCount = false;

        while (count($notesInScale) < count($this->scale))
        {
            foreach ($this->scale as $note)
            {
                if (is_array($note))
                {
                    if (($note[0] == $scale) || ($note[1] == $scale))
                    {
                        $canCount = true;
                    }
                } else
                {
                    if ($note == $scale)
                    {
                        $canCount = true;
                    }
                }

                if ($canCount)
                {
                    if (in_array($note, $notesInScale) == false)
                    {
                        $notesInScale[] = $note;
                    }
                }
            }
        }

        return $notesInScale;
    }

    public function loadSong($song = '')
    {
        if ($song != '')
        {
            $this->song = $song;
        }

        if ($this->song == '')
        {
            throw new TransposerException("No Song Loaded");
        }

        $song_chords = array();

        preg_match_all($this->search, $this->song, $song_chords);

        $u = array_unique($song_chords[0]);

        foreach ($u as $chord)
        {
            if (strlen($chord) > 1 && ($chord{1} == "b" || $chord{1} == "#"))
            {
                array_push($this->formattedChords, substr($chord, 0, 2));
            } 
            else
            {
                array_push($this->formattedChords, substr($chord, 0, 1));
            }
        }

        $this->song = preg_replace($this->search, '|$1|', $this->song);
    }

    public function transpose($from, $to)
    {
        if ($this->song == '')
        {
            throw new TransposerException("No Song Loaded");
        }

        foreach ($this->formattedChords as $note)
        {
            $this->transposeNote($note, $from, $to);
        }

        foreach ($this->formattedChords as &$note)
        {
            $note = "/\|" . $note . "\|/";
        }

        return preg_replace($this->formattedChords, $this->replacementChords, $this->song);
    }

    private function transposeNote($note, $from, $to)
    {
        $fromScale = $this->getNotesInScale($from);
        $toScale = $this->getNotesInScale($to);

        $noteCount = 0;
        $notePos = -1;
        foreach ($fromScale as $fromNote)
        {
            if (is_array($fromNote))
            {
                if (($fromNote[0] == $note) || ($fromNote[1] == $note))
                {
                    $notePos = $noteCount;
                    break;
                }
            } 
            else
            {
                if ($fromNote == $note)
                {
                    $notePos = $noteCount;
                    break;
                }
            }

            $noteCount++;
        }

        if ($notePos !== -1)
        {
            if (is_array($toScale[$notePos]))
            {
                if ($fromScale[$notePos][0] == $note)
                {
                    array_push($this->replacementChords, $toScale[$notePos][0]);
                } 
                else
                {
                    array_push($this->replacementChords, $toScale[$notePos][1]);
                }
            } 
            else
            {
                array_push($this->replacementChords, $toScale[$notePos]);
            }
        } 
        else
        {
            throw new TransposerException("Note Not Found: " . $note);
        }
    }
}

?>