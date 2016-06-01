using System;
using System.Collections.Generic;

public class Project {

    private string mName, mDescription;
    private float mLat, mLon;
    private string mCurrentStage = "";
    private string mImagePath;
    private List<Stage> mStages;

    /// <summary>
    /// Project Constructor
    /// </summary>
    /// <param name="name">The project's name</param>
    /// <param name="description">The project's description</param>
    /// <param name="lat">The project's latitude</param>
    /// <param name="lon">The project's longitude</param>
    /// <param name="imagePath">The path to the project's headerimage</param>
    /// <param name="stages">The stages of the project</param>
    public Project(string name, string description, float lat, float lon, string imagePath, List<Stage> stages)
    {
        mName = name;
        mDescription = description;
        mLat = lat;
        mLon = lon;
        mImagePath = imagePath;
        
        mStages = stages;
    }

    #region Properties
    public string Name
    {
        get { return mName; }
    }

    public string Description
    {
        get { return mDescription; }
    }

    public float Latitude
    {
        get { return mLat; }
    }

    public float Longitude
    {
        get { return mLon; }
    }

    public List<Stage> Stages
    {
        get { return mStages; }
    }

    public string CurrentStage
    {
        get { return mCurrentStage; }
    }

    public string ImagePath
    {
        get { return mImagePath; }
    }
    #endregion

    /// <summary>
    /// Determines the current stage of a project.
    /// </summary>
    /// <param name="stages">The list of stages of this project.</param>
    public void DetermineCurrentStage(List<Stage> stages)
    {
        if (stages.Count > 0)
        {
            for (byte i = 0; i < stages.Count; i++)
            {
                if (DateTime.Compare(DateTime.Today, stages[i].BeginDate) >= 0 && DateTime.Compare(DateTime.Today, stages[i].EndDate) <= 0)
                {
                    // todays date is later than the begindate AND earlier than the enddate of the current stage
                    mCurrentStage = stages[i].Name;
                    break;
                }
            }
        }

        if(mCurrentStage == "")
        {
            mCurrentStage = "Geen fasen beschikbaar.";
        }
    }
}
