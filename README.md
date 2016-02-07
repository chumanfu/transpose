# Transpose
This package will read music for a stringed instrument, from a string and transpose it based on it's current key.

##Song format
The idea behind this class comes from transposing Uke tabs. My wife does this on a regular basis and asked me to make her life easier. The format of the music is mainly song lyrics with the chords above the words which they are supposed to be played on:

```
G                              D7            G
London Bridge is falling down, falling down, falling down
```



##Usage
Create a new Transposer object. You can either provide a song or assign it later.<br>
```$transposer = new ChuManFu\Transposer();```







