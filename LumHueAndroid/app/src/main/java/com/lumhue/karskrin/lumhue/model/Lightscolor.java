package com.lumhue.karskrin.lumhue.model;

/**
 * Created by Karskrin on 06/06/2016.
 */
public class Lightscolor {
    public Integer id;
    public String color;
    public Boolean on = true;
    public String rgbhex;

    public Lightscolor(Integer id, String color, Boolean on, String rgbhex) {
        this.id = id;
        this.color = color;
        this.on = on;
        this.rgbhex = rgbhex;
    }
}
