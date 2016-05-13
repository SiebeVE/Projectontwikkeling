using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class LoadMap : MonoBehaviour {

    // Use this for initialization
	void Start () {
        StartCoroutine(loadImg());
    }

    IEnumerator loadImg()
    {
        WWW www = new WWW(MapManager.URLaddress);
        yield return www;

        GetComponent<RawImage>().texture = www.texture;
    }
}
