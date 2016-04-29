using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class testMap : MonoBehaviour {

    // Use this for initialization
	void Start () {
        StartCoroutine(loadImg());
    }
	
	// Update is called once per frame
	void Update () {
	
	}

    IEnumerator loadImg()
    {
        WWW www = new WWW(MapManager.URLaddress);
        yield return www;

        GetComponent<RawImage>().texture = www.texture;
    }
}
