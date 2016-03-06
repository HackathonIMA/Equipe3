class Share < ActiveRecord::Base
  belongs_to :user
  belongs_to :school
  has_many :interactions
  has_many :supporters, through: :interactions, source: :user

  scope :active, -> {where active: true}

  enum category: {
    complain: 0,  # reclamação
    praise: 1,    # elogio
    notice: 2     # comunicado
  }

  def Share.from_date(date)
    Share.active.where("created_at >= ?", date.to_time.beginning_of_day)
  end

  def supporters_count
    self.interactions.where(support: true).count
  end

  def Share.popular
    Share.joins(:interactions).group("shares.id").order("count(shares.id) DESC")
  end

  def as_json(options = {})
    options.merge!(:include => { :school => self.school })
    super({  }.merge(options || {}))
  end
end
