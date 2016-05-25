using UnityEngine;

public class LoadMap : MonoBehaviour {

    // Use this for initialization
	void Start () {
        StartCoroutine(MapManager.LoadMap(MapManager.URLaddress));
    }
}
