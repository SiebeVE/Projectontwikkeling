using UnityEngine;
using System.Collections;

public class testMap : MonoBehaviour {

	// Use this for initialization
	IEnumerator Start () {
        WWW www = new WWW(MapManager.URLaddress);
        yield return www;

        Renderer r = GetComponent<Renderer>();
        r.material.mainTexture = www.texture;
	}
	
	// Update is called once per frame
	void Update () {
	
	}
}
