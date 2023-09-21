using System;
using System.Collections.Generic;
using System.IO.Ports;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Timers;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;
using System.Net.Http;
using System.Security.Policy;
using BioAI_Desktop.Classes;
using System.Threading;
using System.IO;

namespace BioAI_Desktop
{
    /// <summary>
    /// Логика взаимодействия для MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        #region Variables
        
        System.Timers.Timer aTimer;
        SerialPort currentPort;
        private delegate void updateDelegate(string txt);


        static HttpMessageHandler handler = new HttpClientHandler();
        HttpClient client = new HttpClient(handler, false);


        static List<Cube> cubesList = new List<Cube>();
        static Cube cube;
        bool CubeChoosed = false;
        #endregion

        #region Xaml
        private void Border_MouseEnter(object sender, MouseEventArgs e)
        {
            Border border = (Border)sender;
            border.Opacity = 0.8;
        }

        private void Border_MouseLeave(object sender, MouseEventArgs e)
        {
            Border border = (Border)sender;
            border.Opacity = 1;

        }
        public MainWindow()
        {

            InitializeComponent();
            string[] names = SerialPort.GetPortNames();
            if (names.Length < 3)
            {
                ScrollCom.VerticalScrollBarVisibility = ScrollBarVisibility.Hidden;
            }
            ComsList.ItemsSource = names;

            string[] text = File.ReadAllText("../../Info/Info.txt").Replace("\r", "").Split('\n');
            foreach (string s in text) 
            {
                if(s != "")
                {

                    cubesList.Add(new Cube(s));
                }
            }
            CubesList.ItemsSource = cubesList;

        }
        private void Window_Loaded(object sender, RoutedEventArgs e)
        {
            if (!CubeChoosed || cube == null) return;
            try
            {
                currentPort = new SerialPort(cube.comPortNumber); //Пробуем открыть com-порт с указанным именем
            }
            catch { }


            System.Threading.Thread.Sleep(500); // wait a lot after closing

            currentPort.BaudRate = 9600;
            currentPort.DtrEnable = true;
            currentPort.ReadTimeout = 1000;
            try
            {
                currentPort.Open();
            }
            catch { }

            aTimer = new System.Timers.Timer(1000);
            aTimer.Elapsed += OnTimedEvent;
            aTimer.AutoReset = true;
            aTimer.Enabled = true;

        }
        private void Window_Closing(object sender, System.ComponentModel.CancelEventArgs e)
        {
            aTimer.Enabled = false;
            currentPort.Close();
        }
        #endregion

        #region Logic

        private void ChooseButton_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                
                string Com = (string)ComsList.SelectedItem;
                currentPort = new SerialPort(Com); //Пробуем открыть com-порт с указанным именем
                
                if (currentPort.IsOpen)
                {
                    currentPort.Close();
                }
                currentPort.Open();
                currentPort.BaudRate = 9600;

                currentPort.WriteLine("3");
                currentPort.WriteLine("5");
                currentPort.WriteLine("7");
                Thread.Sleep(2000);
                currentPort.DiscardInBuffer();  // Очищаем буфер от старой информации
                Thread.Sleep(2000);
                currentPort.WriteLine("2");
                Thread.Sleep(1000);
                currentPort.WriteLine("3");
                Thread.Sleep(1000);
                currentPort.WriteLine("2");
                Thread.Sleep(1000);
                currentPort.WriteLine("3");
                Thread.Sleep(1000);
                currentPort.Close();
            }
            catch { }
            ComStackPanel.Visibility = Visibility.Collapsed;
            CubeStackPanel.Visibility = Visibility.Visible; 
        }

        private void CubeChooseButton_Click(object sender, RoutedEventArgs e)
        {
            cube = (Cube)CubesList.SelectedItem;
            cube.comPortNumber = (string)ComsList.SelectedItem;
            CubeStackPanel.Visibility = Visibility.Collapsed;
            MainGrid.Visibility = Visibility.Visible;
            CubeChoosed = true;
            Window_Loaded(sender, e);
        }



        private void OnTimedEvent(object sender, ElapsedEventArgs e)
        {
            if (!currentPort.IsOpen) return;
            try
            {
                currentPort.DiscardInBuffer();  // Очищаем буфер от старой информации
                currentPort.WriteLine("1");
                string strFromPort = currentPort.ReadLine();  // проверяем последнее значение
                lblPortData.Dispatcher.BeginInvoke(new updateDelegate(updateTextBox), strFromPort);
            }
            catch (Exception ex)
            {
                lblPortData.Dispatcher.BeginInvoke(new updateDelegate(updateTextBox), ex.Message);

            }
        }

        private async void updateTextBox(string txt)
        {
            if (txt.Contains("Операция") || txt.Contains("Время")) return;

            string[] strings = txt.Trim().Split(' ');
            //Обновляем данные в UI
            Temperature.Content = Temperature.Content.ToString().Split(':')[0] + ": " + strings[0];
            Humidity.Content = Humidity.Content.ToString().Split(':')[0] + ": " + strings[1];
            SoilMoisture.Content = SoilMoisture.Content.ToString().Split(':')[0] + ": " + Math.Round(100 - Convert.ToInt32(strings[2]) / 10.24, 2);
            Lighting.Content = Lighting.Content.ToString().Split(':')[0] + ": " + Math.Round(100 - Convert.ToInt32(strings[3]) / 10.24, 2);
            pH.Content = pH.Content.ToString().Split(':')[0] + ": " + Math.Round((Convert.ToInt32(strings[6]) * 5 / 1023 + 1.1) * 3.5, 1).ToString().Replace(",", ".");
            if(cube.LastWateration.Date == DateTime.Now.Date)
            {
                LastWateration.Content = "Последний полив: " + cube.LastWateration.TimeOfDay.ToString().Split('.')[0];
            }
            else
            {
                LastWateration.Content = "Последний полив: " + cube.LastWateration.Date.ToString().Replace(" 0:00:00", "") + " " + cube.LastWateration.Hour + ":" + cube.LastWateration.Minute;
                
            }
            LifeExpectancy.Content = "Посажено: " +  cube.plantDate.ToString();
            //Отправляем данные на сервер

            //http://sehriyospace.uz/bioai/report?role=cube&cube_id=1&password=BioAi&temperature=99.9&humidity_air=99&moisture_soil=99&ph=9.9&water_volume=0&light_status=55&cooler_status=1&last_watering_datetime=...
            var result = await client.GetStringAsync($"http://sehriyospace.uz/bioai/report?role=cube&cube_id={cube.id}&password=BioAi&"
                        + $"temperature={strings[0]}&"
                        + $"humidity_air={strings[1]}&"
                        + $"moisture_soil={Math.Round(100 - Convert.ToInt32(strings[2]) / 10.24, 2).ToString().Replace(",", ".")}&"
                        + $"light_status={Math.Round(100 - Convert.ToInt32(strings[3]) / 10.24, 2).ToString().Replace(",", ".")}&"
                        + $"ph={Math.Round((Convert.ToInt32(strings[6]) * 5 / 1023 + 1.1) * 3.5, 1).ToString().Replace(",", ".")}&")
                        + $"last_watering_datetime={cube.LastWateration}";

            //Проверяем изменения на сервере
            
            var answer = await client.GetStringAsync($"http://sehriyospace.uz/bioai/cube?role=cube&cube_id={cube.id}&password=BioAi");
            System.Threading.Thread.Sleep( 1000 );
            List<double> datas = new List<double>();
            //Формат в котором данные приходят с сайта: { "light_status":"73","temperature":"13","cooler_status":"1","water_volume":"0"}

            string Message = answer.Replace("{", "").Replace("}", "").Replace("\"", "");
            
            Dictionary<string, double> commands = new Dictionary<string, double>();
            string[] tempCommands = Message.Split(',');
            
            foreach (string s in tempCommands)
            {
                string k = s.Split(':')[0];
                double v = Convert.ToDouble(s.Split(':')[1].Replace(".", ","));
                commands.Add(k, v);
            }
            if (commands["light_status"] > 0)
            {
                currentPort.WriteLine("2");
            }
            else
            {
                currentPort.WriteLine("3");
            }
            if (commands["cooler_status"] > 0)
            {
                currentPort.WriteLine("4");
            }
            else
            {
                currentPort.WriteLine("5");
            }
            if (commands["water_volume"] > 0)
            {
                currentPort.WriteLine("6");
                for(int i = 0; i < commands["water_volume"]; i++)
                {
                    Thread.Sleep(250);
                }
                commands["water_volume"] = 0;
                cube.LastWateration = DateTime.Now;

                string[] text = File.ReadAllText("../../Info/Info.txt").Replace("\r", "").Split('\n');
                File.Delete("../../Info/Info.txt");
                string s = "";
                foreach (Cube c in cubesList)
                {
                    if(c.id == cube.id)
                    {
                        c.LastWateration = cube.LastWateration;
                    }
                    s += c.plantName + "|" + c.id + "|" + c.plantDate + "|" + c.LastWateration + "\r\n";
                }
                File.WriteAllText("../../Info/Info.txt", s);

            }
            else
            {
                currentPort.WriteLine("7");
            }

        }


        #endregion

        
    }
}

