using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

/// <summary>
/// Instantiates the list of buttons in the map screen.
/// </summary>
public static class InstantiateListScript {

    /// <summary>
    /// Instantiates a button of the current project in the map list.
    /// </summary>
    /// <param name="go">The button to be instantiated</param>
    /// <param name="parent">The parent where the buttons will be instantiated</param>
    /// <param name="projects">The list of projects</param>
    public static void CreateListItemInstance(GameObject go, GameObject parent, List<Project> projects)
    {
        // delete all gameobjects with the tag "list_tem", so a new list can be created
        GameObject[] list_itemsArray = GameObject.FindGameObjectsWithTag("list_item");

        if (list_itemsArray.Length > 0)
        {
            for (byte i = 0; i < list_itemsArray.Length; i++)
            {
                Object.DestroyImmediate(list_itemsArray[i], true);
            }
        }

        for(byte i = 0; i < projects.Count; i++)
        {
            var instance = Object.Instantiate(go);

            instance.name = projects[i].Name;
            instance.transform.Find("Letter").GetComponent<Text>().text = MapManager.alphabet[i].ToString();
            instance.transform.Find("Lbl").GetComponent<Text>().text = projects[i].Name;

            instance.transform.SetParent(parent.transform, false);

            instance.GetComponent<Button>().onClick.AddListener(() => Debug.Log("Click"));
        }
    }
}
