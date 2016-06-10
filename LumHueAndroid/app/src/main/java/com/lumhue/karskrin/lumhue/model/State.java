package com.lumhue.karskrin.lumhue.model;

import java.util.ArrayList;
import java.util.List;

public class State {

    public Boolean on;
    public Integer bri;
    public Integer hue;
    public Integer sat;
    public String effect;
    public List<Float> xy = new ArrayList<Float>();
    public Integer ct;
    public String alert;
    public String colormode;
    public Boolean reachable;

}