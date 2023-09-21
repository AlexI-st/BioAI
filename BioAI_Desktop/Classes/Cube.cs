using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace BioAI_Desktop.Classes
{
    public class Cube
    {
        public string plantName { get; set; }
        public int id { get; set; }
        public string comPortNumber { get; set; }
        public DateTime plantDate { get; set; }
        public DateTime LastWateration { get; set; }


        public Cube(string txt) 
        {
            string[] data = txt.Split('|');
            plantName = data[0];
            id = int.Parse(data[1]);
            plantDate = DateTime.Parse(data[2]);
            LastWateration = DateTime.Parse(data[3]);
        }
        public override string ToString()
        {
            return plantName + ": Id:" + id; 
        }

    }
}
