using UnityEngine;

public class MapLoader : MonoBehaviour {

    /// <summary>
    /// Workaround to get CallLoadMap in MapManager.SetAddress
    /// </summary>
    private static MapLoader loader;

    void Awake()
    {
        loader = this;
    }

    // Use this for initialization
	void Start () {
        // First, be sure the location name is added to the list
        StartCoroutine(MapManager.ReturnLocationName(UIHandler.location_input.text, UIHandler.location_string));

        // Secondly, load the map
        StartCoroutine(MapManager.LoadMap(MapManager.URLaddress));

        InstantiateListScript.CreateListItemInstance(UIHandler.map_list_item, UIHandler.grid, MapManager.TempProjects);
    }

    /// <summary>
    /// Helper method to load the map when a location is prompted.
    /// </summary>
    /// <remarks>Use this in MapManager.SetAddress</remarks>
    public static void CallLoadMap()
    {
        // can't call StartCoroutine in a static function
        // so we need to call it through an instance of the script
        loader.StartCoroutine(MapManager.LoadMap(MapManager.URLaddress));
    }
}
