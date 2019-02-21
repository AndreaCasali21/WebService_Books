using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
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

namespace ServiceClient
{
    /// <summary>
    /// Logica di interazione per MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        private const string url = "10.13.100.25/work/webservices/books/service.php";
        private string Comando, Categoria, Dipartimento,Dipartimento2="",Dipartimento3="";
        public MainWindow()
        {
            InitializeComponent();
            btn_ricerca.IsEnabled = false;
            txt_libri.Visibility = System.Windows.Visibility.Hidden;
            txt_reparti.Visibility = System.Windows.Visibility.Hidden;
            lbl_1.Visibility = System.Windows.Visibility.Hidden;
            lbl_2.Visibility = System.Windows.Visibility.Hidden;
        }
        
        private void Btn_ricerca_Click(object sender, RoutedEventArgs e) {
            string url = "http://localhost/rest/?name=" + 1;
            Getrequest(url);
        }

        /**
         * Inviatore della richiesta
         */
        async void Getrequest(string url)
        {
            using (HttpClient client = new HttpClient())
            {
                using (HttpResponseMessage response = await client.GetAsync(url))
                {
                    using (HttpContent content = response.Content)
                    {//possiamo usare HttpContentHeader headers = content.Headers;
                        string mycontent = await content.ReadAsStringAsync();
                        for (int x = 0; x < mycontent.Length; x++)
                        {
                            lst_elenco.Items.Add(mycontent[x]);
                        }
                    }

                }

            }
        }        
        private void cmb_selezione_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            if(cmb_selezione.SelectedIndex == 0)
            {
                btn_ricerca.IsEnabled = true;
                Comando = "COUNT";
                Categoria = "fumetti";
                Dipartimento = "ultimi arrivi";
                txt_libri.Visibility = System.Windows.Visibility.Hidden;
                txt_reparti.Visibility = System.Windows.Visibility.Hidden;
                lbl_1.Visibility = System.Windows.Visibility.Hidden;
                lbl_2.Visibility = System.Windows.Visibility.Hidden;
            }
            if (cmb_selezione.SelectedIndex == 1)
            {
                Comando = "IS";
                Dipartimento = "Da non perdere";
                Dipartimento2 = "Offerte speciali";
                Dipartimento3 = "Remainders";
                Categoria = "";
                txt_libri.Visibility = System.Windows.Visibility.Hidden;
                txt_reparti.Visibility = System.Windows.Visibility.Hidden;
                lbl_1.Visibility = System.Windows.Visibility.Hidden;
                lbl_2.Visibility = System.Windows.Visibility.Hidden;
            }
            if (cmb_selezione.SelectedIndex == 2)
            {
                txt_libri.Visibility = System.Windows.Visibility.Visible;
                txt_reparti.Visibility = System.Windows.Visibility.Visible;
                lbl_1.Visibility = System.Windows.Visibility.Visible;
                lbl_1.Content = "Inserisci la data iniziale";
                lbl_2.Visibility = System.Windows.Visibility.Visible;
                lbl_2.Content = "Inserisci la data finale";
            }
        }
    }
}
