using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class test : MonoBehaviour {

    public static Text connection, loc;
    public Button b;
    public static GameObject i;
    

	// Use this for initialization
	void Start () {
        //b = GameObject.Find("map").GetComponent<Button>();
        //loc = GameObject.Find("loc").GetComponent<Text>();
        //connection = GameObject.Find("Text").GetComponent<Text>();
        //i = GameObject.Find("Image");
        //b.onClick.AddListener(() => Application.OpenURL("https://www.google.be"));

        InvokeRepeating("CallDetermineLocation", 0, 900);

        //b.onClick.AddListener(() => StartCoroutine(MapManager.LoadMap(MapManager.URLaddress)));
	}
	
	// Update is called once per frame
	void Update () {
        //loc.text = "LAT: " + LocationManager.Latitude + "; LONG: " + LocationManager.Longitude;
        //connection.text = MapManager.URLaddress;
    }

    private void CallDetermineLocation()
    {
        Debug.Log("Called");
        LocationManager.DetermineLocation();
    }
}
