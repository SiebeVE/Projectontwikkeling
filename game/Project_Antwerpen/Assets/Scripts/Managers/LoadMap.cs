using UnityEngine;
using UnityEngine.UI;
using System.Collections;

public class LoadMap : MonoBehaviour {

    // Use this for initialization
	void Start () {
        //StartCoroutine(loadImg());
        StartCoroutine(MapManager.LoadMap(MapManager.URLaddress));
    }

    //IEnumerator loadImg()
    //{
    //    WWW www = new WWW(MapManager.URLaddress);
    //    yield return www;

    //    GetComponent<RawImage>().texture = www.texture;
    //}
}
