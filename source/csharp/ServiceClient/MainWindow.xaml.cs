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
            string url = "https://github.com/AndreaCasali21/WebService_Books.git";
            if (Dipartimento2 == "")
            {
                Getrequest(
                    new List<KeyValuePair<string, string>>() {
                    new KeyValuePair<string, string>("Query", Comando+" books.category IS "+Categoria+" AND "+" books.department IS "+Dipartimento)
                    }, url
                );
            }
            else
            {
                Getrequest(
                    new List<KeyValuePair<string, string>>() {
                    new KeyValuePair<string, string>("Query", Comando+" books.category IS "+Categoria+" AND "+" books.department IS "+Dipartimento+","+Dipartimento2+","+Dipartimento3)
                    }, url
                );
            }
       }

        /**
         * Inviatore della richiesta
         */
        async void Getrequest(IEnumerable<KeyValuePair<string, string>> request,string url) {
            // creo il messaggio HTTP dalla richiesta fornita
            HttpContent cont = new FormUrlEncodedContent(request);
            using (HttpClient client = new HttpClient())
            {
                using (HttpResponseMessage response = await client.PostAsync(url, cont))
                {
                    using (HttpContent content = response.Content)
                    {
                        string mycontent = await content.ReadAsStringAsync();
                        for(int x = 0; x < mycontent.Length; x++)
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
