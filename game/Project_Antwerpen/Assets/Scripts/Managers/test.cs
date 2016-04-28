using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class test : MonoBehaviour {

    public static Text connection;
    public string IPaddress = "72.14.192.0";

	// Use this for initialization
	void Start () {
        connection = GameObject.Find("con").GetComponent<Text>();

        NetworkManager.CheckInternetConnection(IPaddress);
	}
	
	// Update is called once per frame
	void Update () {
        if (NetworkManager.IsConnected)
        {
            connection.text = "Connected!";
        }
        else
        {
            connection.text = "No internet";
        }
	}
}
