var display_users = new SegmentDisplay("display_users");
display_users.pattern         = "########";
display_users.cornerType      = 2;
display_users.displayType     = 7;
display_users.displayAngle    = 6;
display_users.digitHeight     = 10;
display_users.digitWidth      = 6;
display_users.digitDistance   = 1.25;
display_users.segmentWidth    = 1;
display_users.segmentDistance = 0.25;
display_users.colorOn         = "rgba(0, 0, 0, 0.9)";
display_users.colorOff        = "rgba(0, 0, 0, 0.1)";

var display_corpus = new SegmentDisplay("display_corpus");
display_corpus.pattern         = "########";
display_corpus.cornerType      = 2;
display_corpus.displayType     = 7;
display_corpus.displayAngle    = 6;
display_corpus.digitHeight     = 10;
display_corpus.digitWidth      = 6;
display_corpus.digitDistance   = 1.25;
display_corpus.segmentWidth    = 1;
display_corpus.segmentDistance = 0.5;
display_corpus.colorOn         = "rgba(0, 0, 0, 0.9)";
display_corpus.colorOff        = "rgba(0, 0, 0, 0.1)";

var display_sites = new SegmentDisplay("display_sites");
display_sites.pattern         = "########";
display_sites.cornerType      = 2;
display_sites.displayType     = 7;
display_sites.displayAngle    = 6;
display_sites.digitHeight     = 10;
display_sites.digitWidth      = 6;
display_sites.digitDistance   = 1.25;
display_sites.segmentWidth    = 1;
display_sites.segmentDistance = 0.5;
display_sites.colorOn         = "rgba(0, 0, 0, 0.9)";
display_sites.colorOff        = "rgba(0, 0, 0, 0.1)";

function animate_users($value) {
    var num = $value;
    while (num.length < 8){
        num =' '+num;
    }
    display_users.setValue(num);
    window.setInterval('animate_users(num)',1);
}

function animate_corpus($value) {
    var num = $value;
    while (num.length < 8){
        num =' '+num;
    }
    display_corpus.setValue(num);
    window.setInterval('animate_corpus(num)',1);
}

function animate_sites($value) {
    var num = $value;
    while (num.length < 8){
        num =' '+num;
    }
    display_sites.setValue(num);
    window.setInterval('animate_sites(num)',1);
}

